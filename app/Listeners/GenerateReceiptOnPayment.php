<?php
namespace App\Listeners;
use App\Events\PaymentReceived;
use App\Notifications\PaymentReceiptNotification;

class GenerateReceiptOnPayment
{
    public function handle(PaymentReceived $event)
    {
        // Send receipt notification
        if ($event->bill->patient) {
            $event->bill->patient->notify(new PaymentReceiptNotification($event->bill, $event->payment));
        }
    }
}
