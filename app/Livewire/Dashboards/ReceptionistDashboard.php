<?php

namespace App\Livewire\Dashboards;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\InsuranceRequest;
use App\Models\PatientAccount;
use Carbon\Carbon;

class ReceptionistDashboard extends Component
{
    public $fromDate;
    public $toDate;

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfDay();
        $this->toDate = Carbon::now()->endOfDay();
    }

    public function getMetrics()
    {
        return [
            'today_appointments' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])->count(),
            'pending_insurance' => InsuranceRequest::where('status', 'pending')->count(),
            'unpaid_bills' => PatientAccount::where('balance', '>', 0)->count(),
            'completed_today' => Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
                ->where('status', 'completed')->count(),
        ];
    }

    public function getTodayAppointments()
    {
        return Appointment::whereBetween('appointment_date', [$this->fromDate, $this->toDate])
            ->with('patient', 'doctor')
            ->orderBy('appointment_date')
            ->get();
    }

    public function getPendingInsurance()
    {
        return InsuranceRequest::where('status', 'pending')
            ->with('appointment.patient', 'insuranceCompany')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function getOverdueBills()
    {
        return PatientAccount::where('balance', '>', 0)
            ->where('updated_at', '<', Carbon::now()->subDays(30))
            ->with('patient')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function createAppointment()
    {
        return redirect()->route('appointments.create');
    }

    public function processPayment($patientAccountId)
    {
        return redirect()->route('payments.create', ['patient_account_id' => $patientAccountId]);
    }

    public function render()
    {
        return view('livewire.dashboards.receptionist-dashboard', [
            'metrics' => $this->getMetrics(),
            'appointments' => $this->getTodayAppointments(),
            'pendingInsurance' => $this->getPendingInsurance(),
            'overdueBills' => $this->getOverdueBills(),
        ]);
    }
}
