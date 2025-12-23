<?php

namespace App\Livewire\Patients;

use App\Models\Patient;
use App\Models\Bill;
use App\Models\Payment;
use Livewire\Component;

class Show extends Component
{
    public Patient $patient;
    public $activeTab = 'personal';

    // Pagination
    public $billsPerPage = 5;
    public $paymentsPerPage = 5;
    public $appointmentsPerPage = 5;

    // ==================== LIFECYCLE ====================

    public function mount(Patient $patient)
    {
        $this->patient = $patient;
        $this->patient->load([
            'insuranceCompany',
            'bills.billItems.procedure',
            'payments.bill',
            'appointments',
            'visits.procedures'
        ]);
    }

    public function render()
    {
        return view('livewire.patients.show', [
            'bills' => $this->patient->bills()
                ->latest('bill_date')
                ->paginate($this->billsPerPage),
            'payments' => $this->patient->payments()
                ->latest('payment_date')
                ->paginate($this->paymentsPerPage),
            'appointments' => $this->patient->appointments()
                ->latest('appointment_date')
                ->paginate($this->appointmentsPerPage),
        ]);
    }

    // ==================== TAB MANAGEMENT ====================

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ==================== DATA METHODS ====================

    public function getPaymentSummary()
    {
        return $this->patient->getPaymentSummary();
    }

    public function getInsuranceData()
    {
        if (!$this->patient->isInsurance()) {
            return null;
        }

        return [
            'company' => $this->patient->insuranceCompany,
            'card_number' => $this->patient->insurance_card_number,
            'policyholder' => $this->patient->insurance_policyholder,
            'expiry_date' => $this->patient->insurance_expiry_date,
        ];
    }

    public function getBillStats()
    {
        $bills = $this->patient->bills;

        return [
            'total_bills' => $bills->count(),
            'total_amount' => $bills->sum('total_amount'),
            'paid_amount' => $bills->sum('paid_amount'),
            'balance_due' => $bills->sum(fn($bill) => $bill->getBalance()),
            'unpaid_count' => $bills->whereIn('status', ['issued', 'partially_paid'])->count(),
        ];
    }

    public function getPaymentStats()
    {
        $payments = $this->patient->payments()->completed()->get();

        return [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'last_payment' => $payments->first()?->payment_date,
        ];
    }

    public function getMedicalHistory()
    {
        return $this->patient->visits()
            ->with('procedures', 'doctor')
            ->latest('visit_date')
            ->limit(10)
            ->get();
    }
}
