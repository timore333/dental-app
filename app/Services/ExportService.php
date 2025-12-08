<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportService
{
    /**
     * Export to Excel
     */
    public function exportToExcel($data, $headers, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        foreach ($headers as $index => $header) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
        }

        // Add data
        $row = 2;
        foreach ($data as $item) {
            $col = 1;
            foreach ($headers as $header) {
                $key = strtolower(str_replace(' ', '_', $header));
                $value = $item[$key] ?? $item->$key ?? '';
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        // Format columns
        foreach ($headers as $index => $header) {
            $sheet->getColumnDimensionByColumn($index + 1)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filepath = storage_path('app/exports/' . $filename . '.xlsx');
        $writer->save($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Export to PDF
     */
    public function exportToPDF($data, $headers, $filename)
    {
        $html = $this->generateHTMLTable($data, $headers);

        $pdf = Pdf::loadHTML($html);
        $filepath = storage_path('app/exports/' . $filename . '.pdf');
        $pdf->save($filepath);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Export to CSV
     */
    public function exportToCSV($data, $headers, $filename)
    {
        $filepath = storage_path('app/exports/' . $filename . '.csv');
        $file = fopen($filepath, 'w');

        // Write headers
        fputcsv($file, $headers);

        // Write data
        foreach ($data as $item) {
            $row = [];
            foreach ($headers as $header) {
                $key = strtolower(str_replace(' ', '_', $header));
                $row[] = $item[$key] ?? $item->$key ?? '';
            }
            fputcsv($file, $row);
        }

        fclose($file);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Generate HTML table
     */
    private function generateHTMLTable($data, $headers)
    {
        $html = '<table border="1"><thead><tr>';

        foreach ($headers as $header) {
            $html .= '<th>' . $header . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        foreach ($data as $item) {
            $html .= '<tr>';
            foreach ($headers as $header) {
                $key = strtolower(str_replace(' ', '_', $header));
                $value = $item[$key] ?? $item->$key ?? '';
                $html .= '<td>' . $value . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    /**
     * Stream download
     */
    public function streamDownload($content, $filename, $format)
    {
        $headers = [
            'Content-Type' => $this->getContentType($format),
            'Content-Disposition' => 'attachment; filename=' . $filename . '.' . $format,
        ];

        return response($content, 200, $headers);
    }

    /**
     * Get MIME type
     */
    private function getContentType($format)
    {
        return match($format) {
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'pdf' => 'application/pdf',
            'csv' => 'text/csv',
            default => 'application/octet-stream'
        };
    }
}
