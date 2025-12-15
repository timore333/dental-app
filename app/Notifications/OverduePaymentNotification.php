<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OverduePaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $payment;
    public $daysOverdue;

    public function __construct(Payment $payment, $daysOverdue = 0)
    {
        $this->payment = $payment;
        $this->daysOverdue = $daysOverdue;
    }

    public function via($notifiable)
    {
        $preferences = $notifiable->notificationPreferences;
        $channels = [];
        if ($preferences && $preferences->isSmsEnabled()) $channels[] = 'sms';
        if ($preferences && $preferences->isEmailEnabled()) $channels[] = 'mail';
        if ($preferences && $preferences->isInAppEnabled()) $channels[] = 'database';
        return $channels ?: ['mail'];
    }

    public function toMail($notifiable)
    {
        $amount = number_format($this->payment->amount, 2);

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting('Payment Overdue')
            ->line("Your bill of {$amount} EGP is now overdue.")
            ->when($this->daysOverdue, function($message) {
                return $message->line("It is {$this->daysOverdue} days past the due date.");
            })
            ->action('Pay Now', route('payments.pay', $this->payment))
            ->line('For payment options, please contact us at Thnaya Clinic.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Payment Overdue',
            'message' => "Your bill of " . number_format($this->payment->amount, 2) . " EGP is overdue",
            'type' => 'payment',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'days_overdue' => $this->daysOverdue,
        ];
    }
}
