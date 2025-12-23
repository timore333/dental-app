<?php

namespace App\Services;

use App\Models\Payment;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PaymentExportService
{
    public function exportToExcel(array $filters)
    {
        $query = Payment::with('patient', 'bill')
            ->where('status', 'completed');

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        return Excel::download(
            new PaymentExport($query->get()),
            "payments-" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    public function exportReceiptsAsZip(array $paymentIds)
    {
        $zip = new ZipArchive();
        $zipPath = storage_path('app/temp/receipts-' . now()->timestamp . '.zip');

        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            throw new \Exception('Cannot create ZIP file');
        }

        Payment::whereIn('id', $paymentIds)->each(function ($payment) use ($zip) {
            $pdf = Pdf::loadView('payments.receipts.print-template', ['payment' => $payment]);
            $filename = "Receipt-{$payment->receipt_number}.pdf";
            $zip->addFromString($filename, $pdf->output());
        });

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function generatePaymentSummaryReport(array $filters = [])
    {
        $query = Payment::where('status', 'completed');

        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        $payments = $query->get();

        $summary = [
            'total_payments' => $payments->count(),
            'total_amount' => (float)$payments->sum('amount'),
            'by_method' => $payments->groupBy('payment_method')->map(fn($group) => [
                'count' => $group->count(),
                'total' => (float)$group->sum('amount'),
            ]),
            'by_date' => $payments->groupBy(fn($p) => $p->payment_date->format('Y-m-d'))->map(fn($group) => [
                'count' => $group->count(),
                'total' => (float)$group->sum('amount'),
            ]),
        ];

        return $summary;
    }
}
