<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\ReportService;
use App\Services\ExportService;
use Carbon\Carbon;

class FinancialReport extends Component
{
    use WithPagination;

    public $fromDate;
    public $toDate;
    public $reportType = 'summary';
    public $insuranceCompanyFilter = null;
    public $procedureFilter = null;
    public $statusFilter = null;

    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->toDate = Carbon::now()->format('Y-m-d');
    }

    public function updateReportType($type)
    {
        $this->reportType = $type;
        $this->resetPage();
    }

    public function getReportData()
    {
        $reportService = new ReportService();
        return $reportService->getFinancialReport(
            $this->fromDate,
            $this->toDate,
            $this->reportType
        );
    }

    public function exportExcel()
    {
        $data = $this->getReportData();
        $exportService = new ExportService();

        return $exportService->exportToExcel(
            $data,
            ['Date', 'Description', 'Amount', 'Method', 'Status'],
            'financial_report_' . date('Y-m-d')
        );
    }

    public function exportPDF()
    {
        $data = $this->getReportData();
        $exportService = new ExportService();

        return $exportService->exportToPDF(
            $data,
            ['Date', 'Description', 'Amount', 'Method', 'Status'],
            'financial_report_' . date('Y-m-d')
        );
    }

    public function render()
    {
        $reportData = $this->getReportData();

        return view('livewire.reports.financial-report', [
            'reportData' => $reportData,
            'insuranceCompanies' => \App\Models\InsuranceCompany::all(),
            'procedures' => \App\Models\Procedure::all(),
        ]);
    }
}
