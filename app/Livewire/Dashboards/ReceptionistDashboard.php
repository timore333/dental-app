<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\InsuranceRequest;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReceptionistDashboard extends Component
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
     * Get receptionist dashboard metrics
     */
    public function getMetrics(): array
    {
        return [
            // ✅ FIXED: All appointments (not filtered by user)
            'total_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->count(),

            // ✅ FIXED: Scheduled appointments
            'scheduled_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->where('status', 'scheduled')
                ->count(),

            // ✅ FIXED: Completed appointments
            'completed_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),

            // ✅ FIXED: New patients registered
            'new_patients' => Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),

            // ✅ FIXED: Total patients in system
            'total_patients' => Patient::count(),

            // ✅ FIXED: Pending insurance requests
            'pending_insurance_requests' => InsuranceRequest::where('status', 'pending')
                ->count(),

            // ✅ FIXED: Pending appointments
            'pending_appointments' => Appointment::where('status', 'pending')
                ->count(),
        ];
    }

    /**
     * Get chart data
     */
    public function getChartData(): array
    {
        // Appointment Status Chart
        $appointmentStatus = Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // New Patients Over Time
        $patientGrowth = Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Appointments by Doctor
        $appointmentsByDoctor = DB::table('appointments')
            ->join('users', 'appointments.doctor_id', '=', 'users.id')
            ->whereBetween('appointments.appointment_date', [$this->fromDate, $this->toDate])
            ->selectRaw('users.name, COUNT(*) as count')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'name');

        // Insurance Requests Status
        $insuranceStatus = InsuranceRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'appointmentStatus' => [
                'labels' => array_keys($appointmentStatus->toArray()),
                'data' => array_values($appointmentStatus->toArray()),
            ],
            'patientGrowth' => [
                'labels' => array_keys($patientGrowth->toArray()),
                'data' => array_values($patientGrowth->toArray()),
            ],
            'appointmentsByDoctor' => [
                'labels' => array_keys($appointmentsByDoctor->toArray()),
                'data' => array_values($appointmentsByDoctor->toArray()),
            ],
            'insuranceStatus' => [
                'labels' => array_keys($insuranceStatus->toArray()),
                'data' => array_values($insuranceStatus->toArray()),
            ],
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.dashboards.receptionist-dashboard', [
            'metrics' => $this->getMetrics(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
