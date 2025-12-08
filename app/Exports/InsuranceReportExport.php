<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InsuranceReportExport implements FromArray, WithHeadings, WithStyles
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
                'company' => $item->insuranceCompany->name ?? $item->name ?? 'N/A',
                'total_requests' => $item->total_requests ?? $item->count ?? 0,
                'total_amount' => $item->total_amount ?? $item->amount ?? 0,
                'approval_rate' => $item->approval_rate ?? 'N/A',
                'status' => $item->status ?? 'N/A',
            ];
        }

        return $arrayData;
    }

    public function headings(): array
    {
        return ['Insurance Company', 'Total Requests', 'Total Amount', 'Approval Rate', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
        ];
    }
}
