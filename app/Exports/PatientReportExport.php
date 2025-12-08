<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientReportExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $arrayData = [];

        foreach ($this->data as $item) {
            $arrayData[] = [
                'name' => $item->name,
                'age' => $item->age ?? 'N/A',
                'gender' => ucfirst($item->gender ?? 'N/A'),
                'phone' => $item->phone ?? 'N/A',
                'email' => $item->email ?? 'N/A',
                'patient_type' => ucfirst($item->patient_type ?? 'N/A'),
                'created_at' => $item->created_at->format('Y-m-d'),
            ];
        }

        return $arrayData;
    }

    public function headings(): array
    {
        return ['Name', 'Age', 'Gender', 'Phone', 'Email', 'Patient Type', 'Created At'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }
}
