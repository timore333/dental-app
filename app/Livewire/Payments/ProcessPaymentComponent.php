<?php

namespace App\Livewire\Payments;

use App\Models\Bill;
use App\Models\Patient;
use App\Models\AdvanceCredit;
use App\Services\PaymentService;
use App\Services\AdvanceCreditService;
use Livewire\Component;

class ProcessPaymentComponent extends Component
{
    public $bill = null;
    public $patient = null;
    public $amount = 0;
    public $paymentMethod = 'cash';
    public $paymentDate;
    public $referenceNumber = '';
    public $notes = '';
    public $isAdvancePayment = false;
    public $applyAdvanceCredit = false;
    public $advanceCreditId = null;
    public $currentTab = 'payment';
    public $availableCredits = [];
    public $suggestedAmounts = [];

    protected $rules = [
        'amount' => 'required|numeric|min:0.01|max:999999.99',
        'paymentMethod' => 'required|in:cash,cheque,card,bank_transfer,insurance',
        'paymentDate' => 'required|date|before_or_equal:today',
        'referenceNumber' => 'nullable|string|max:255',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount($billId = null, $patientId = null)
    {
        $this->paymentDate = now()->format('Y-m-d');

        if ($billId) {
            $this->bill = Bill::findOrFail($billId);
            $this->patient = $this->bill->patient;
            $this->amount = $this->bill->getAmountDue();
        } elseif ($patientId) {
            $this->patient = Patient::findOrFail($patientId);
        }

        $this->calculateSuggestedAmounts();
        $this->loadAvailableCredits();
    }

    public function calculateSuggestedAmounts()
    {
        if ($this->bill) {
            $amountDue = $this->bill->getAmountDue();
            $this->suggestedAmounts = [
                round($amountDue, 2),
                round($amountDue * 0.5, 2),
                round($amountDue * 0.25, 2),
            ];
        }
    }

    public function loadAvailableCredits()
    {
        if ($this->patient) {
            $this->availableCredits = $this->patient->advanceCredits()
                ->active()
                ->get()
                ->toArray();
        }
    }

    public function toggleAdvancePayment()
    {
        $this->isAdvancePayment = !$this->isAdvancePayment;
        if ($this->isAdvancePayment) {
            $this->bill = null;
        }
    }

    public function updateAmount($amount)
    {
        $this->amount = $amount;
        $this->validate(['amount' => 'required|numeric|min:0.01']);
    }

    public function processPayment()
    {
        $this->validate();

        try {
            $service = app(PaymentService::class);

            if ($this->isAdvancePayment) {
                $payment = $service->recordAdvancePayment($this->patient, [
                    'amount' => $this->amount,
                    'payment_method' => $this->paymentMethod,
                    'payment_date' => $this->paymentDate,
                    'reference_number' => $this->referenceNumber ?: null,
                    'notes' => $this->notes ?: null,
                ]);
            } else {
                $payment = $service->processPartialPayment($this->bill, [
                    'amount' => $this->amount,
                    'payment_method' => $this->paymentMethod,
                    'payment_date' => $this->paymentDate,
                    'reference_number' => $this->referenceNumber ?: null,
                    'notes' => $this->notes ?: null,
                    'apply_advance_credit' => $this->applyAdvanceCredit,
                    'advance_credit_id' => $this->advanceCreditId,
                ]);
            }

            $this->currentTab = 'confirmation';
            $this->dispatch('paymentProcessed', $payment->id);
            $this->js('$dispatch("refreshPayments")');

        } catch (\Exception $e) {
            $this->addError('general', $e->getMessage());
        }
    }

    public function printReceipt($paymentId)
    {
        return redirect()->route('payments.print', $paymentId);
    }

    public function render()
    {
        return view('livewire.payments.process-payment-component');
    }
}
