<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class DoctorDashboard extends Component
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
     * Get doctor dashboard metrics
     */
    public function getMetrics(): array
    {
        $doctor = auth()->user();

        return [
            // ✅ FIXED: Appointments for logged-in doctor only
            'total_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->count(),

            // ✅ FIXED: Completed appointments for logged-in doctor
            'completed_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),

            // ✅ FIXED: Visits recorded by logged-in doctor
            'total_visits' => Visit::where('doctor_id', $doctor->id)
                ->whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),

            // ✅ FIXED: Total earnings from payments (simplified - sum all payments in range)
            // NOTE: If you need doctor-specific earnings, add a doctor_id column to payments table
            'total_earnings' => DB::table('payments')
                ->whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->sum('amount') ?? 0,

            // ✅ FIXED: Pending appointments for logged-in doctor
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->count(),
        ];
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(): array
    {
        $doctor = auth()->user();

        // Appointment Status Chart
        $appointmentStatus = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$this->fromDate, $this->toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // ✅ FIXED: Payment method distribution (all payments)
        $earningsByMethod = DB::table('payments')
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        // Procedures performed by logged-in doctor
        $proceduresPerformed = DB::table('visits')
            ->where('visits.doctor_id', $doctor->id)
            ->whereBetween('visits.created_at', [$this->fromDate, $this->toDate])
            ->join('visit_procedures', 'visits.id', '=', 'visit_procedures.visit_id')
            ->join('procedures', 'visit_procedures.procedure_id', '=', 'procedures.id')
            ->selectRaw('procedures.name, COUNT(*) as count')
            ->groupBy('procedures.id', 'procedures.name')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'name');

        // Appointments Over Time
        $appointmentsOverTime = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(appointment_date) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return [
            'appointmentStatus' => [
                'labels' => array_keys($appointmentStatus->toArray()),
                'data' => array_values($appointmentStatus->toArray()),
            ],
            'earningsByMethod' => [
                'labels' => array_keys($earningsByMethod->toArray()),
                'data' => array_values($earningsByMethod->toArray()),
            ],
            'procedures' => [
                'labels' => array_keys($proceduresPerformed->toArray()),
                'data' => array_values($proceduresPerformed->toArray()),
            ],
            'appointmentsOverTime' => [
                'labels' => array_keys($appointmentsOverTime->toArray()),
                'data' => array_values($appointmentsOverTime->toArray()),
            ],
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboards.doctor-dashboard', [
            'metrics' => $this->getMetrics(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
