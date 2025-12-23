<?php

namespace App\Livewire\Dashboards;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\InsuranceRequest;
use App\Models\Patient;
use App\Models\Payment;
use App\Traits\hasDateRange;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Receptionist Dashboard')]
class ReceptionistDashboard extends Component
{
use hasDateRange;
    public function mount(): void
    {
        $this->setDateRange('30days');
    }



    /**
     * Get receptionist dashboard metrics
     */
    public function getMetrics(): array
    {
        return [
            'total_patients' => Patient::count(),
            'appointments_today' => Appointment::whereDate('start', Carbon::today())->count(),
            'revenue_month' => Payment::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('amount'),
            'pending_payments' => Bill::where('total_amount', '>', 0)->count(),
            'total_appointments' => Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
                ->count(),
            'scheduled_appointments' => Appointment::whereDate('start', '>=', Carbon::today())
                ->where('status', 'scheduled')
                ->count(),
            'completed_appointments' => Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')
                ->count(),
            'new_patients' => Patient::whereBetween('created_at', [$this->fromDate, $this->toDate])
                ->count(),
            'pending_insurance_requests' => InsuranceRequest::where('status', 'pending')
                ->count(),
            'pending_appointments' => Appointment::where('status', 'pending')
                ->count(),
            'total_payments_today' => Payment::whereDate('created_at', Carbon::today())
                ->count(),
        ];
    }

    /**
     * Get today's appointments
     */
    public function getTodayAppointments()
    {
        return Appointment::with(['patient', 'doctor'])
            ->whereDate('start', Carbon::today())
            ->orderBy('start', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent appointments
     */
    public function getRecentAppointments()
    {
        return Appointment::with(['patient', 'doctor'])
            ->whereBetween('start', [Carbon::now()->subDays(7), Carbon::now()])
            ->orderBy('start', 'desc')
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
            ->where('total_amount', '>', 0)
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Get recent payments
     */
    public function getRecentPayments()
    {
        return Payment::with(['bill.patient'])
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    /**
     * Get chart data
     */
    public function getChartData(): array
    {
        // Appointment Status Chart
        $appointmentStatus = Appointment::whereBetween('start', [$this->fromDate, $this->toDate])
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
            ->whereBetween('appointments.start', [$this->fromDate, $this->toDate])
            ->selectRaw('users.name, COUNT(*) as count')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'name');

        // Insurance Requests Status
        $insuranceStatus = InsuranceRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Payment methods used
        $paymentMethods = Payment::whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

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
            'paymentMethods' => [
                'labels' => array_keys($paymentMethods->toArray()),
                'data' => array_values($paymentMethods->toArray()),
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
            'recentPayments' => $this->getRecentPayments(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
