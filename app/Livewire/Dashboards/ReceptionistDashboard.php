<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\InsuranceRequest;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReceptionistDashboard extends Component
{
    public string $dateRange = '30days';
    public $fromDate;
    public $toDate;

    public function mount(): void
    {
        $this->setDateRange('30days');
    }

    /**
     * Set the date range for metrics
     */
    public function setDateRange(string $range = '30days'): void
    {
        $this->dateRange = $range;
        $today = Carbon::now();

        match ($range) {
            '7days' => [
                $this->fromDate = $today->copy()->subDays(7)->startOfDay(),
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
            'total_patients' => Patient::count(),
            'appointments_today' => Appointment::whereDate('appointment_date', Carbon::today())
                ->count(),
            'revenue_month' => Bill::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'pending_payments' => Bill::where('status', 'pending')
                ->orWhere('status', 'partial')
                ->count(),
            'total_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->count(),
            'scheduled_appointments' => Appointment::whereDate('appointment_date', '>=', Carbon::today())
                ->where('status', 'scheduled')
                ->count(),
            'completed_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),
            'new_patients' => Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),
            'pending_insurance_requests' => InsuranceRequest::where('status', 'pending')
                ->count(),
            'pending_appointments' => Appointment::where('status', 'pending')
                ->count(),
        ];
    }

    /**
     * Get today's appointments
     */
    public function getTodayAppointments()
    {
        return Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_date', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent appointments
     */
    public function getRecentAppointments()
    {
        return Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [Carbon::now()->subDays(7), Carbon::now()])
            ->orderBy('appointment_date', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get pending insurance requests
     */
    public function getPendingInsuranceRequests()
    {
        return InsuranceRequest::with(['appointment.patient', 'insuranceCompany'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get overdue bills
     */
    public function getOverdueBills()
    {
        return Bill::with('patient')
            ->where('due_date', '<', Carbon::now())
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();
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
            'todayAppointments' => $this->getTodayAppointments(),
            'recentAppointments' => $this->getRecentAppointments(),
            'pendingInsuranceRequests' => $this->getPendingInsuranceRequests(),
            'overdueBills' => $this->getOverdueBills(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
