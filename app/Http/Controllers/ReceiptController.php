<?php
namespace App\Http\Controllers;
use App\Models\Payment;

class ReceiptController extends Controller
{
    public function show($id)
    {
        $receipt = Payment::findOrFail($id);
        return view('receipts.show', ['receipt' => $receipt]);
    }

    public function print($id)
    {
        $receipt = Payment::findOrFail($id);
        return view('receipts.print', ['receipt' => $receipt]);
    }

    public function email($id)
    {
        $receipt = Payment::findOrFail($id);
        $receipt->bill->patient->notify(new PaymentReceiptNotification($receipt->bill, $receipt));
        return back()->with('success', __('Receipt sent'));
    }
}
