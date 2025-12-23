<?php

namespace App\Livewire\Payments;

use App\Models\AdvanceCredit;
use App\Models\Patient;
use App\Services\AdvanceCreditService;
use Livewire\Component;

class AdvanceCreditManagerComponent extends Component
{
    public $patientId;
    public $patient;
    public $credits = [];
    public $selectedCreditId = null;
    public $showRefundForm = false;
    public $refundAmount = 0;
    public $autoApply = false;

    public function mount($patientId)
    {
        $this->patientId = $patientId;
        $this->patient = Patient::findOrFail($patientId);
        $this->loadCredits();
        $this->autoApply = config('payment.auto_apply_credits', false);
    }

    public function loadCredits()
    {
        $this->credits = $this->patient->advanceCredits()
            ->with('allocations')
            ->orderBy('expires_at', 'asc')
            ->get()
            ->toArray();
    }

    public function applyToNextBill()
    {
        try {
            $service = app(AdvanceCreditService::class);
            $amount = $service->applyToNextBill($this->patient);

            if ($amount > 0) {
                session()->flash('message', __('messages.credit_applied', ['amount' => $amount]));
            } else {
                session()->flash('info', __('messages.no_applicable_credit'));
            }

            $this->loadCredits();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function toggleRefundForm($creditId)
    {
        $this->selectedCreditId = $creditId;
        $this->showRefundForm = !$this->showRefundForm;
        $this->refundAmount = 0;
    }

    public function processRefund()
    {
        try {
            $credit = AdvanceCredit::findOrFail($this->selectedCreditId);
            app(AdvanceCreditService::class)->issueCreditRefund($credit, $this->refundAmount ?: null);

            session()->flash('message', __('messages.refund_processed'));
            $this->showRefundForm = false;
            $this->loadCredits();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.payments.advance-credit-manager-component');
    }
}
