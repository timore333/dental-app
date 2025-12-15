<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
            ->greeting('Payment Received')
            ->line("Thank you! We have received your payment.")
            ->line("**Amount:** {$amount} EGP")
            ->line("**Date:** " . $this->payment->paid_date->format('F j, Y'))
            ->action('View Receipt', route('payments.show', $this->payment))
            ->line('Receipt attached below.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Payment Received',
            'message' => 'Thank you for your payment of ' . number_format($this->payment->amount, 2) . ' EGP',
            'type' => 'payment',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
        ];
    }
}
