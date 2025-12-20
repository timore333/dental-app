<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\PatientsImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PatientImport extends Component
{
    use WithFileUploads;

    public $file;

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new PatientsImport, $this->file);

            session()->flash('message', __('messages.patient.imported'));
            return redirect()->route('patients.index');
        } catch (\Exception $e) {
            session()->flash('error', __('messages.patient.import_failed') . ': ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'file_number');
        $sheet->setCellValue('B1', 'name');
        $sheet->setCellValue('C1', 'phone');
        $sheet->setCellValue('D1', 'email');
        $sheet->setCellValue('E1', 'date_of_birth');
        $sheet->setCellValue('F1', 'address');
        $sheet->setCellValue('G1', 'patient_type');
        $sheet->setCellValue('H1', 'gender');

        // Sample data
        $sheet->setCellValue('A2', '1001');
        $sheet->setCellValue('B2', 'Ahmed Ali');
        $sheet->setCellValue('C2', '01012345678');
        $sheet->setCellValue('D2', 'ahmed@example.com');
        $sheet->setCellValue('E2', '1990-01-15');
        $sheet->setCellValue('F2', 'Cairo, Egypt');
        $sheet->setCellValue('G2', 'should be (cash or insurance) only');
        $sheet->setCellValue('H2', 'should be (male or female) only');

        // Style headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4472C4');
        $sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('FFFFFF');

        // Auto-size columns
        foreach(range('A','G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $filename = 'patients_template.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }



    public function render()
    {
        return view('livewire.patients.patient-import');
    }
}

