<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\AdvanceCredit;
use App\Models\PaymentAllocation;
use App\Events\PaymentReceived;
use App\Events\PartialPaymentApplied;
use App\Events\AdvancePaymentCreated;
use App\Events\PaymentReversed;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function recordAdvancePayment(Patient $patient, array $data): Payment
    {
        return DB::transaction(function () use ($patient, $data) {
            $payment = Payment::create([
                'patient_id' => $patient->id,
                'bill_id' => null,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'completed',
            ]);

            if (config('payment.overpayment_handling') === 'create_credit') {
                AdvanceCredit::create([
                    'patient_id' => $patient->id,
                    'amount' => $data['amount'],
                    'remaining_balance' => $data['amount'],
                    'source_type' => 'advance_payment',
                    'source_reference_id' => $payment->id,
                    'expires_at' => now()->addDays(config('payment.advance_credit_expiry_days', 365)),
                ]);
            }

            event(new AdvancePaymentCreated($payment));
            return $payment;
        });
    }

    public function processPartialPayment(Bill $bill, array $data): Payment
    {
        return DB::transaction(function () use ($bill, $data) {
            $this->validatePayment($bill, $data);

            $payment = Payment::create([
                'bill_id' => $bill->id,
                'patient_id' => $bill->patient_id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'completed',
            ]);

            // Mark bill as issued on first payment
            if ($bill->status === 'draft') {
                $bill->update(['status' => 'issued']);
            }

            // Apply payment to bill
            $bill->applyPayment($data['amount']);

            // Handle advance credit application
            if ($data['apply_advance_credit'] ?? false) {
                $credit = AdvanceCredit::find($data['advance_credit_id'] ?? null);
                if ($credit) {
                    $this->applyAdvanceCreditToBill($bill, $credit);
                }
            }

            // Handle overpayment
            $amountDue = $bill->getAmountDue();
            if ($amountDue < 0) {
                $this->handleOverpayment($bill, abs($amountDue), $payment);
            }

            PaymentAllocation::create([
                'payment_id' => $payment->id,
                'bill_id' => $bill->id,
                'allocated_amount' => $data['amount'],
                'allocation_date' => now(),
            ]);

            event(new PaymentReceived($payment));
            if ($this->isPartialPayment($bill)) {
                event(new PartialPaymentApplied($payment));
            }

            return $payment;
        });
    }

    public function applyAdvanceCreditToBill(Bill $bill, AdvanceCredit $credit, ?float $amount = null): float
    {
        $amountDue = $bill->getAmountDue();
        if ($amountDue <= 0 || $credit->isExpired()) {
            return 0;
        }

        $applicableAmount = min($amount ?? $credit->getAvailableBalance(), $amountDue);

        $applied = $credit->applyToPayment($applicableAmount);
        $bill->applyPayment($applied);

        PaymentAllocation::create([
            'bill_id' => $bill->id,
            'advance_credit_id' => $credit->id,
            'allocated_amount' => $applied,
            'allocation_date' => now(),
        ]);

        return $applied;
    }

    public function handleOverpayment(Bill $bill, float $overpaymentAmount, Payment $payment): void
    {
        if (config('payment.overpayment_handling') === 'create_credit') {
            AdvanceCredit::create([
                'patient_id' => $bill->patient_id,
                'amount' => $overpaymentAmount,
                'remaining_balance' => $overpaymentAmount,
                'source_type' => 'overpayment',
                'source_reference_id' => $payment->id,
            ]);
        }
    }

    public function validatePayment(Bill $bill, array $data): void
    {
        if ($bill->status === 'cancelled') {
            throw new \Exception(__('messages.cannot_pay_cancelled_bill'));
        }

        if ($data['amount'] <= 0) {
            throw new \Exception(__('messages.payment_amount_must_be_positive'));
        }

        if ($data['payment_method'] === 'insurance' && !$bill->patient->isInsurance()) {
            throw new \Exception(__('messages.only_insurance_patients_can_use_insurance_method'));
        }

        if (!empty($data['reference_number'])) {
            $exists = Payment::where('reference_number', $data['reference_number'])
                ->where('payment_method', $data['payment_method'])
                ->exists();

            if ($exists) {
                throw new \Exception(__('messages.reference_number_already_exists'));
            }
        }
    }

    public function reversePayment(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            if ($payment->status === 'cancelled') {
                throw new \Exception(__('messages.payment_already_cancelled'));
            }

            $payment->markAsCancelled();

            if ($payment->bill_id) {
                $bill = $payment->bill;
                $bill->decrement('paid_amount', $payment->amount);

                // Recalculate bill status
                if ($bill->paid_amount > 0) {
                    $bill->update(['status' => 'partially_paid']);
                } else {
                    $bill->update(['status' => 'issued']);
                }
            }

            // Reverse any allocations
            $payment->allocations()->delete();

            event(new PaymentReversed($payment));
        });
    }

    public function allocatePaymentToMultipleBills(Patient $patient, float $amount, string $method, array $billIds, string $distribution = 'proportional'): array
    {
        return DB::transaction(function () use ($patient, $amount, $method, $billIds, $distribution) {
            $bills = Bill::whereIn('id', $billIds)
                ->where('patient_id', $patient->id)
                ->where('status', '!=', 'fully_paid')
                ->get();

            $allocations = [];
            $remainingAmount = $amount;

            foreach ($bills as $bill) {
                $amountDue = $bill->getAmountDue();

                $allocateAmount = match ($distribution) {
                    'equal' => $amount / count($bills),
                    'oldest_first' => min($amountDue, $remainingAmount),
                    'proportional' => ($amountDue / $bills->sum('getAmountDue')) * $amount,
                    default => min($amountDue, $remainingAmount),
                };

                $allocateAmount = min($allocateAmount, $amountDue, $remainingAmount);

                if ($allocateAmount > 0) {
                    $payment = Payment::create([
                        'bill_id' => $bill->id,
                        'patient_id' => $patient->id,
                        'amount' => $allocateAmount,
                        'payment_method' => $method,
                        'status' => 'completed',
                    ]);

                    $bill->applyPayment($allocateAmount);

                    PaymentAllocation::create([
                        'payment_id' => $payment->id,
                        'bill_id' => $bill->id,
                        'allocated_amount' => $allocateAmount,
                        'allocation_date' => now(),
                    ]);

                    $allocations[] = $payment;
                    $remainingAmount -= $allocateAmount;
                }

                if ($remainingAmount <= 0) break;
            }

            return $allocations;
        });
    }

    private function isPartialPayment(Bill $bill): bool
    {
        return $bill->getAmountDue() > 0;
    }
}
