<?php

namespace App\Notifications;

use App\Models\Insurance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InsuranceApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $insurance;

    public function __construct(Insurance $insurance)
    {
        $this->insurance = $insurance;
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
        $coverage = number_format($this->insurance->coverage_amount ?? 0, 2);

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting('Insurance Approved! ✅')
            ->line('Good news! Your insurance claim has been APPROVED.')
            ->line("**Coverage Amount:** {$coverage} EGP")
            ->action('View Details', route('insurance.show', $this->insurance))
            ->line('Thank you for choosing Thnaya Clinic.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Insurance Approved ✅',
            'message' => 'Your insurance claim has been approved',
            'type' => 'insurance',
            'insurance_id' => $this->insurance->id,
            'coverage_amount' => $this->insurance->coverage_amount,
            'status' => 'approved',
        ];
    }
}
