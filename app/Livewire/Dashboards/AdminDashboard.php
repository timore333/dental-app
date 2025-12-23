<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\InsuranceRequest;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use App\Traits\hasDateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Dashboard')]
class AdminDashboard extends Component
{
  use hasDateRange;

    public function mount(): void
    {
        $this->setDateRange();
    }


    /**
     * Get dashboard metrics
     */
    public function getMetrics(): array
    {
        return [
            'total_patients' => Patient::count(),
            'total_appointments' => Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
                ->count(),
            'completed_appointments' => Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),
            'total_revenue' => Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->sum('amount') ?? 0,
            'pending_insurance' => InsuranceRequest::where('status', 'pending')
                ->count(),
            'active_doctors' => User::doctor()
                ->count(),
            'total_payments_count' => Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),
            'pending_approvals' => InsuranceRequest::where('status', 'pending')
                ->count(),
        ];
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(): array
    {
        // Appointment Status Chart
        $appointmentStatusData = Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Revenue by Payment Method Chart
        $revenueByType = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // Top Procedures by Frequency
        $procedures = DB::table('visits')
            ->whereBetween('visits.created_at', [$this->fromDate, $this->toDate])
            ->join('visit_procedures', 'visits.id', '=', 'visit_procedures.visit_id')
            ->join('procedures', 'visit_procedures.procedure_id', '=', 'procedures.id')
            ->selectRaw('procedures.name, COUNT(*) as count')
            ->groupBy('procedures.id', 'procedures.name')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'name');

        // Patient Growth Over Time
        $patientGrowth = Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Daily Revenue Trend
        $dailyRevenue = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        return [
            'appointmentStatus' => [
                'labels' => array_keys($appointmentStatusData->toArray()),
                'data' => array_values($appointmentStatusData->toArray()),
            ],
            'revenueByType' => [
                'labels' => array_keys($revenueByType->toArray()),
                'data' => array_values($revenueByType->toArray()),
            ],
            'procedures' => [
                'labels' => array_keys($procedures->toArray()),
                'data' => array_values($procedures->toArray()),
            ],
            'patientGrowth' => [
                'labels' => array_keys($patientGrowth->toArray()),
                'data' => array_values($patientGrowth->toArray()),
            ],
            'dailyRevenue' => [
                'labels' => array_keys($dailyRevenue->toArray()),
                'data' => array_values($dailyRevenue->toArray()),
            ],
        ];
    }

    /**
     * Get recent payments
     */
    public function getRecentPayments()
    {
        return Payment::with(['bill.patient'])
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboards.admin-dashboard', [
            'metrics' => $this->getMetrics(),
            'chartData' => $this->getChartData(),
            'recentPayments' => $this->getRecentPayments(),
        ]);
    }
}
