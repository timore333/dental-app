<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Bill;
use App\Models\Account;

class PaymentService
{
    /**
     * Record payment for bill
     */
    public function recordPayment(
        Bill $bill,
        float $amount,
        string $method,
        ?string $referenceNumber = null,
        ?string $notes = null
    ): Payment {
        // Create payment record
        $payment = Payment::create([
            'bill_id' => $bill->id,
            'amount' => $amount,
            'payment_method' => $method,
            'payment_date' => now(),
            'reference_number' => $referenceNumber,
            'receipt_number' => $this->generateReceiptNumber(),
            'notes' => $notes,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Create receipt
        Receipt::create([
            'payment_id' => $payment->id,
            'receipt_number' => $payment->receipt_number,
            'receipt_date' => now(),
        ]);

        // Update bill status and amount
        $this->applyPaymentToBill($bill, $payment);

        // Record in patient account
        $this->recordPaymentInAccount($bill, $payment);

        return $payment;
    }

    /**
     * Generate unique receipt number
     */
    public function generateReceiptNumber(): string
    {
        $year = now()->format('Y');
        $lastPayment = Payment::where('receipt_number', 'like', "RCP-$year-%")
            ->orderByDesc('receipt_number')
            ->first();

        $sequence = 1;
        if ($lastPayment) {
            $lastSequence = (int) explode('-', $lastPayment->receipt_number)[2];
            $sequence = $lastSequence + 1;
        }

        return sprintf('RCP-%d-%04d', $year, $sequence);
    }

    /**
     * Apply payment to bill and update status
     */
    public function applyPaymentToBill(Bill $bill, Payment $payment): void
    {
        $newPaidAmount = $bill->paid_amount + $payment->amount;

        // Determine new status
        if ($newPaidAmount >= $bill->total_amount) {
            $status = 'fully_paid';
            $newPaidAmount = $bill->total_amount;
        } elseif ($newPaidAmount > 0) {
            $status = 'partially_paid';
        } else {
            $status = $bill->status;
        }

        $bill->update([
            'paid_amount' => $newPaidAmount,
            'status' => $status,
        ]);
    }

    /**
     * Record payment in patient account ledger
     */
    public function recordPaymentInAccount(Bill $bill, Payment $payment): void
    {
        if ($bill->patient) {
            $account = $bill->patient->account;
            if (!$account) {
                $account = Account::create([
                    'accountable_id' => $bill->patient_id,
                    'accountable_type' => 'App\Models\Patient',
                    'balance' => 0,
                ]);
            }

            // Record credit (payment received)
            $account->credit(
                $payment->amount,
                'payment',
                $payment->id,
                "Payment for Bill #{$bill->bill_number}"
            );
        }
    }

    /**
     * Get payment history for bill
     */
    public function getPaymentHistory(Bill $bill)
    {
        return $bill->payments()
            ->with('receipt')
            ->orderByDesc('payment_date')
            ->get();
    }
}
