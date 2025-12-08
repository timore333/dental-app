<?php
namespace App\Notifications;
use App\Models\Bill;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceiptNotification extends Notification
{
    public $bill;
    public $payment;

    public function __construct(Bill $bill, $payment = null)
    {
        $this->bill = $bill;
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('Payment Receipt'))
            ->view('emails.payment-receipt', [
                'bill' => $this->bill,
                'payment' => $this->payment,
            ]);
    }
}
