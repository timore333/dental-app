<?php
namespace App\Livewire\Bills;
use App\Models\Bill;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CashBillingFlow extends Component
{
    public $bill;
    public $paymentAmount = 0;
    public $paymentMethod = 'cash';
    public $paymentDate;
    public $referenceNumber = '';
    public $tab = 'bill';

    public $showConfirmPayment = false;

    protected $rules = [
        'paymentAmount' => 'required|numeric|min:0.01',
        'paymentMethod' => 'required|in:cash,cheque,card,bank_transfer',
        'paymentDate' => 'required|date|before_or_equal:now',
        'referenceNumber' => 'nullable|string|max:255',
    ];

    public function mount($billId)
    {
        $this->bill = Bill::findOrFail($billId);
        $this->paymentAmount = $this->bill->amount_due;
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.billing.cash-billing-flow', [
            'bill' => $this->bill,
        ]);
    }

    public function recordPayment()
    {
        $this->validate();

        try {
            $payment = Payment::create([
                'bill_id' => $this->bill->id,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => $this->paymentDate,
                'reference_number' => $this->referenceNumber,
                'created_by' => Auth::id(),
            ]);

            // Update bill status
            $this->bill->total_paid += $this->paymentAmount;
            $this->bill->amount_due = max(0, $this->bill->amount_due - $this->paymentAmount);

            if ($this->bill->amount_due == 0) {
                $this->bill->payment_status = 'fully_paid';
            } else {
                $this->bill->payment_status = 'partially_paid';
            }

            $this->bill->save();

            event(new \App\Events\PaymentReceived($this->bill, $payment));

            $this->dispatch('notify', type: 'success', message: __('Payment recorded successfully'));
            $this->tab = 'receipt';
        } catch (\Exception $e) {
            $this->addError('general', __('Error recording payment'));
        }
    }

    public function generateReceipt()
    {
        return redirect()->route('receipts.show', ['receipt' => $this->bill->payment()->latest()->first()->id]);
    }
}
?>
