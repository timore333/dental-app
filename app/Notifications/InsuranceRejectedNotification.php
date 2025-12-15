<?php

namespace App\Notifications;


use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class InsuranceRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $patient;
    public $reason;

    public function __construct(Patient $patient, $reason = null)
    {
        $this->patient = $patient;
        $this->reason = $reason ?? 'Please contact us for more information.';
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
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting('Insurance Claim Rejected ❌')
            ->line('Unfortunately, your insurance claim has been rejected.')
            ->line("**Reason:** {$this->reason}")
            ->action('Contact Us', route('contact'))
            ->line('We are here to help if you have any questions.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Insurance Claim Rejected',
            'message' => 'Your insurance claim has been rejected',
            'type' => 'insurance',
            'insurance_id' => $this->patient->id,
            'reason' => $this->reason,
            'status' => 'rejected',
        ];
    }
}
