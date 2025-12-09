<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\InsuranceRequest;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminDashboard extends Component
{
    public string $dateRange = '30days';
    public ?\DateTime $fromDate = null;
    public ?\DateTime $toDate = null;

    public function mount(): void
    {
        $this->setDateRange('30days');
    }

    /**
     * Set the date range for metrics
     */
    public function setDateRange(string $range): void
    {
        $this->dateRange = $range;
        $today = Carbon::now();

        match($range) {
            '7days' => [
                $this->fromDate = $today->copy()->subDays(7)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
            '30days' => [
                $this->fromDate = $today->copy()->subDays(30)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
            '90days' => [
                $this->fromDate = $today->copy()->subDays(90)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
            'yearly' => [
                $this->fromDate = $today->copy()->startOfYear(),
                $this->toDate = $today->endOfYear(),
            ],
            default => [
                $this->fromDate = $today->copy()->subDays(30)->startOfDay(),
                $this->toDate = $today->endOfDay(),
            ],
        };
    }

    /**
     * Get dashboard metrics
     */
    public function getMetrics(): array
    {
        return [
            'total_patients' => Patient::count(),

            'total_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->count(),

            'completed_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),

            // ✅ FIXED: Remove status filter - payments table doesn't have status column
            'total_revenue' => DB::table('payments')
                ->whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->sum('amount') ?? 0,

            'pending_insurance' => InsuranceRequest::where('status', 'pending')
                ->count(),

            'active_doctors' => User::doctor()
                ->active()
                ->count(),

            'pending_approvals' => DB::table('insurance_requests')
                ->where('status', 'pending')
                ->count(),

            'overdue_bills' => DB::table('accounts')
                ->where('balance', '>', 0)
                ->where('updated_at', '<', Carbon::now()->subDays(30))
                ->count(),
        ];
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(): array
    {
        // Appointment Status Chart
        $appointmentStatusData = Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // ✅ FIXED: Revenue by Payment Method Chart (removed status filter)
        $revenueByType = DB::table('payments')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
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
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboards.admin-dashboard', [
            'metrics' => $this->getMetrics(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
