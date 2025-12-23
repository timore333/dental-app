<?php

namespace App\Services;

use App\Models\AdvanceCredit;
use App\Models\Patient;
use App\Models\Payment;
use App\Events\AdvanceCreditExpired;
use Illuminate\Support\Facades\DB;

class AdvanceCreditService
{
    public function createFromPayment(Payment $payment, float $amount): AdvanceCredit
    {
        return AdvanceCredit::create([
            'patient_id' => $payment->patient_id,
            'amount' => $amount,
            'remaining_balance' => $amount,
            'source_type' => 'overpayment',
            'source_reference_id' => $payment->id,
        ]);
    }

    public function getAvailableCredits(Patient $patient): \Illuminate\Database\Eloquent\Collection
    {
        return $patient->advanceCredits()
            ->active()
            ->orderBy('expires_at', 'asc')
            ->get();
    }

    public function applyToNextBill(Patient $patient): float
    {
        $credits = $this->getAvailableCredits($patient);
        $bill = $patient->bills()
            ->where('status', '!=', 'fully_paid')
            ->orderBy('bill_date', 'asc')
            ->first();

        if (!$bill || $credits->isEmpty()) {
            return 0;
        }

        $applied = 0;
        foreach ($credits as $credit) {
            if ($bill->getAmountDue() <= 0) break;

            $amountToApply = min($credit->getAvailableBalance(), $bill->getAmountDue());
            $credit->applyToPayment($amountToApply);
            $bill->applyPayment($amountToApply);
            $applied += $amountToApply;
        }

        return $applied;
    }

    public function expireOutdatedCredits(): int
    {
        return AdvanceCredit::where('expires_at', '<', now())
            ->where('remaining_balance', '>', 0)
            ->update(['remaining_balance' => 0]);
    }

    public function getCreditHistory(AdvanceCredit $credit)
    {
        return $credit->allocations()
            ->with('payment', 'bill')
            ->orderBy('allocation_date', 'desc')
            ->get();
    }

    public function issueCreditRefund(AdvanceCredit $credit, float $amount = null): void
    {
        $refundAmount = $amount ?? $credit->remaining_balance;

        DB::transaction(function () use ($credit, $refundAmount) {
            if ($refundAmount > $credit->remaining_balance) {
                throw new \Exception(__('messages.refund_amount_exceeds_balance'));
            }

            $credit->decrement('remaining_balance', $refundAmount);
            $credit->update(['expires_at' => now()]);
        });
    }

    public function autoApplyCredits(Patient $patient): void
    {
        if (!config('payment.auto_apply_credits', false)) {
            return;
        }

        while ($this->applyToNextBill($patient) > 0) {
            // Continue applying credits
        }
    }
}
