<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Services\ExportService;

class ReportController extends Controller
{
    protected $reportService;
    protected $exportService;

    public function __construct(ReportService $reportService, ExportService $exportService)
    {
        $this->reportService = $reportService;
        $this->exportService = $exportService;
    }

    public function index()
    {
        return view('reports.index');
    }

    public function financial()
    {
        return view('livewire.reports.financial-report');
    }

    public function patient()
    {
        return view('livewire.reports.patient-report');
    }

    public function insurance()
    {
        return view('livewire.reports.insurance-report');
    }

    public function performance()
    {
        return view('livewire.reports.performance-report');
    }

    public function exportFinancial(Request $request)
    {
        $data = $this->reportService->getFinancialReport(
            $request->from_date,
            $request->to_date,
            $request->type ?? 'summary'
        );

        return $this->exportService->exportToExcel(
            $data,
            ['Date', 'Description', 'Amount', 'Type', 'Status'],
            'financial_report_' . date('Y-m-d')
        );
    }

    public function exportPatient(Request $request)
    {
        $data = $this->reportService->getPatientReport(
            $request->from_date,
            $request->to_date,
            $request->type ?? 'demographics'
        );

        return $this->exportService->exportToExcel(
            $data,
            ['Name', 'Age', 'Gender', 'Phone', 'Email'],
            'patient_report_' . date('Y-m-d')
        );
    }

    public function exportInsurance(Request $request)
    {
        $data = $this->reportService->getInsuranceReport(
            $request->from_date,
            $request->to_date,
            $request->type ?? 'summary'
        );

        return $this->exportService->exportToExcel(
            $data,
            ['Company', 'Total Requests', 'Total Amount', 'Status'],
            'insurance_report_' . date('Y-m-d')
        );
    }
}
