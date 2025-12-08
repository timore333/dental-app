<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;
use Carbon\Carbon;

class PatientReport extends Component
{
    use WithPagination;

    public $fromDate;
    public $toDate;
    public $reportType = 'demographics';
    public $sortBy = 'created_at';

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
        $query = Patient::whereBetween('created_at', [$this->fromDate, $this->toDate]);

        return match($this->reportType) {
            'demographics' => $this->getDemographicsData($query),
            'activity' => $this->getActivityData($query),
            'financial' => $this->getFinancialData($query),
            default => $query->paginate(20)
        };
    }

    protected function getDemographicsData($query)
    {
        return $query->selectRaw('age, gender, patient_type, COUNT(*) as count')
            ->groupBy('age', 'gender', 'patient_type')
            ->paginate(20);
    }

    protected function getActivityData($query)
    {
        return $query->with('appointments', 'visits')
            ->selectRaw('patients.*, COUNT(appointments.id) as appointment_count, COUNT(visits.id) as visit_count')
            ->leftJoin('appointments', 'patients.id', '=', 'appointments.patient_id')
            ->leftJoin('visits', 'patients.id', '=', 'visits.patient_id')
            ->groupBy('patients.id')
            ->paginate(20);
    }

    protected function getFinancialData($query)
    {
        return $query->selectRaw('patients.*, SUM(COALESCE(payments.amount, 0)) as total_spent')
            ->leftJoin('payments', function($join) {
                $join->on('patients.id', '=', 'payments.patient_id')
                    ->where('payments.status', '=', 'completed');
            })
            ->groupBy('patients.id')
            ->orderByDesc('total_spent')
            ->paginate(20);
    }

    public function exportExcel()
    {
        $data = $this->getReportData();
        return response()->download('export_patient_' . date('Y-m-d') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.reports.patient-report', [
            'reportData' => $this->getReportData(),
        ]);
    }
}
