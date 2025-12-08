<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LedgerExport implements FromArray, WithHeadings, WithStyles
{
    protected $entries;
    protected $title;

    public function __construct($entries, $title = 'Ledger')
    {
        $this->entries = $entries;
        $this->title = $title;
    }

    public function array(): array
    {
        $arrayData = [];
        $runningBalance = 0;

        foreach ($this->entries as $entry) {
            if ($entry->type === 'debit') {
                $runningBalance += $entry->amount;
            } else {
                $runningBalance -= $entry->amount;
            }

            $arrayData[] = [
                'date' => \Carbon\Carbon::parse($entry->transaction_date)->format('Y-m-d'),
                'description' => $entry->description,
                'debit' => $entry->type === 'debit' ? $entry->amount : '',
                'credit' => $entry->type === 'credit' ? $entry->amount : '',
                'balance' => $runningBalance,
            ];
        }

        // Add total row
        $totalDebits = collect($this->entries)->where('type', 'debit')->sum('amount');
        $totalCredits = collect($this->entries)->where('type', 'credit')->sum('amount');

        $arrayData[] = [
            'date' => '',
            'description' => 'TOTAL',
            'debit' => $totalDebits,
            'credit' => $totalCredits,
            'balance' => $totalDebits - $totalCredits,
        ];

        return $arrayData;
    }

    public function headings(): array
    {
        return ['Date', 'Description', 'Debit', 'Credit', 'Balance'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D3D3D3']]],
            $sheet->getHighestRow() => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E6E6FA']]],
        ];
    }
}
