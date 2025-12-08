<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InsuranceRequest;
use App\Models\InsuranceCompany;
use Carbon\Carbon;

class InsuranceReport extends Component
{
    use WithPagination;

    public $fromDate;
    public $toDate;
    public $reportType = 'summary';
    public $insuranceCompanyId = null;

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
        $query = InsuranceRequest::whereBetween('created_at', [$this->fromDate, $this->toDate]);

        if ($this->insuranceCompanyId) {
            $query->where('insurance_company_id', $this->insuranceCompanyId);
        }

        return match($this->reportType) {
            'summary' => $this->getSummaryData($query),
            'requests' => $this->getRequestsData($query),
            'claims' => $this->getClaimsData($query),
            'performance' => $this->getPerformanceData($query),
            default => $query->paginate(20)
        };
    }

    protected function getSummaryData($query)
    {
        return $query->selectRaw('insurance_company_id, COUNT(*) as total_requests, SUM(estimated_cost) as total_amount')
            ->with('insuranceCompany')
            ->groupBy('insurance_company_id')
            ->paginate(20);
    }

    protected function getRequestsData($query)
    {
        return $query->with('appointment.patient', 'insuranceCompany')
            ->selectRaw('*, CASE WHEN status = "approved" THEN "Approved" WHEN status = "rejected" THEN "Rejected" ELSE "Pending" END as status_label')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    protected function getClaimsData($query)
    {
        return $query->with('appointment.procedures')
            ->where('status', 'approved')
            ->paginate(20);
    }

    protected function getPerformanceData($query)
    {
        $data = \DB::table('insurance_requests')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->join('insurance_companies', 'insurance_requests.insurance_company_id', '=', 'insurance_companies.id')
            ->selectRaw('
                insurance_companies.name,
                COUNT(*) as total_requests,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
                ROUND(SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as approval_rate
            ')
            ->groupBy('insurance_companies.id', 'insurance_companies.name')
            ->paginate(20);

        return $data;
    }

    public function exportExcel()
    {
        $data = $this->getReportData();
        return response()->download('export_insurance_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPDF()
    {
        $data = $this->getReportData();
        return response()->download('export_insurance_' . date('Y-m-d') . '.pdf');
    }

    public function render()
    {
        return view('livewire.reports.insurance-report', [
            'reportData' => $this->getReportData(),
            'insuranceCompanies' => InsuranceCompany::all(),
        ]);
    }
}
