<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromArray, WithHeadings, WithStyles
{
    protected $data;
    protected $totals;

    public function __construct($data, $totals = [])
    {
        $this->data = $data;
        $this->totals = $totals;
    }

    public function array(): array
    {
        $arrayData = [];

        foreach ($this->data as $item) {
            $arrayData[] = [
                'date' => $item['date'] ?? $item->created_at->format('Y-m-d'),
                'description' => $item['description'] ?? 'Payment',
                'amount' => $item['amount'] ?? 0,
                'type' => $item['type'] ?? $item->payment_method,
                'status' => $item['status'] ?? 'completed',
            ];
        }

        // Add total row
        $total = collect($arrayData)->sum('amount');
        $arrayData[] = [
            'date' => '',
            'description' => 'TOTAL',
            'amount' => $total,
            'type' => '',
            'status' => '',
        ];

        return $arrayData;
    }

    public function headings(): array
    {
        return ['Date', 'Description', 'Amount', 'Type', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
            $sheet->getHighestRow() => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E6E6FA']]],
        ];
    }
}
