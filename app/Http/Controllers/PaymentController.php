<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Bill;
use App\Models\Patient;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function store(StorePaymentRequest $request)
    {
        try {
            if ($request->boolean('is_advance_payment')) {
                $patient = Patient::findOrFail($request->patient_id);
                $payment = $this->paymentService->recordAdvancePayment($patient, $request->validated());
            } else {
                $bill = Bill::findOrFail($request->bill_id);
                $payment = $this->paymentService->processPartialPayment($bill, $request->validated());
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.payment_recorded_successfully'),
                'payment' => $payment->load('bill', 'patient'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(Payment $payment)
    {
        return response()->json($payment->load('bill', 'patient', 'allocations'));
    }

    public function update(Payment $payment, UpdatePaymentRequest $request)
    {
        $payment->update($request->validated());
        return response()->json(['success' => true, 'payment' => $payment]);
    }

    public function destroy(Payment $payment)
    {
        try {
            $this->paymentService->reversePayment($payment);
            return response()->json(['success' => true, 'message' => __('messages.payment_reversed')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function receipt(Payment $payment)
    {
        return view('payments.receipt', ['payment' => $payment->load('bill', 'patient')]);
    }

    public function emailReceipt(Payment $payment)
    {
        \Mail::send('payments.receipts.email-template', ['payment' => $payment], function ($m) use ($payment) {
            $m->to($payment->patient->email)
              ->subject(__('messages.receipt') . ' #' . $payment->receipt_number);
        });

        return response()->json(['success' => true, 'message' => __('messages.receipt_sent')]);
    }

    public function printReceipt(Payment $payment)
{
    $payment->load('bill.billItems', 'patient');

    $pdf = PDF::loadView('payments.receipts.print-template', [
        'payment' => $payment,
    ]);

    $pdf->setPaper('A4', 'portrait');
    $pdf->setOption('isPhpEnabled', true);
    $pdf->setOption('isRemoteEnabled', true);

    return $pdf->download("receipt-{$payment->receipt_number}.pdf");
}
}
