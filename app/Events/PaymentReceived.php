<?php
namespace App\Events;
use App\Models\Bill;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class PaymentReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bill;
    public $payment;

    public function __construct(Bill $bill, $payment = null)
    {
        $this->bill = $bill;
        $this->payment = $payment;
    }
}
