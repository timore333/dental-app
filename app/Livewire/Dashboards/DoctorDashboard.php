<?php

namespace App\Livewire\Dashboards;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\User;
use Carbon\Carbon;

class DoctorDashboard extends Component
{
    public $doctor_id;
    public $fromDate;
    public $toDate;
    public $todayOnly = false;

    public function mount()
    {
        $this->doctor_id = auth()->id();
        $this->fromDate = Carbon::now()->startOfDay();
        $this->toDate = Carbon::now()->endOfDay();
    }

    public function toggleTodayFilter()
    {
        $this->todayOnly = !$this->todayOnly;

        if ($this->todayOnly) {
            $this->fromDate = Carbon::now()->startOfDay();
            $this->toDate = Carbon::now()->endOfDay();
        }
    }

    public function getMetrics()
    {
        $query = Appointment::where('doctor_id', $this->doctor_id)
            ->whereBetween('appointment_date', [$this->fromDate, $this->toDate]);

        return [
            'today_appointments' => $query->count(),
            'completed_visits' => $query->where('status', 'completed')->count(),
            'pending_appointments' => $query->where('status', 'scheduled')->count(),
            'total_earnings' => \DB::table('payments')
                ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
                ->where('appointments.doctor_id', $this->doctor_id)
                ->whereBetween('payments.created_at', [$this->fromDate, $this->toDate])
                ->where('payments.status', 'completed')
                ->sum('payments.amount'),
        ];
    }

    public function getUpcomingAppointments()
    {
        return Appointment::where('doctor_id', $this->doctor_id)
            ->whereBetween('appointment_date', [$this->fromDate, $this->toDate])
            ->orderBy('appointment_date')
            ->get();
    }

    public function getVisitsSummary()
    {
        return Visit::where('doctor_id', $this->doctor_id)
            ->whereBetween('created_at', [$this->fromDate, $this->toDate])
            ->with('patient', 'procedures')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function recordVisit($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->doctor_id !== $this->doctor_id) {
            abort(403);
        }

        return redirect()->route('visits.create', ['appointment_id' => $appointmentId]);
    }

    public function completeAppointment($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->doctor_id !== $this->doctor_id) {
            abort(403);
        }

        $appointment->update(['status' => 'completed']);

        $this->dispatch('success', message: __('Appointment marked as completed'));
    }

    public function render()
    {
        return view('livewire.dashboards.doctor-dashboard', [
            'metrics' => $this->getMetrics(),
            'appointments' => $this->getUpcomingAppointments(),
            'visits' => $this->getVisitsSummary(),
        ]);
    }
}
