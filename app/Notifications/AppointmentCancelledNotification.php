<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AppointmentCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $appointment;
    public $reason;

    public function __construct(Appointment $appointment, $reason = null)
    {
        $this->appointment = $appointment;
        $this->reason = $reason;
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
        $date = $this->appointment->appointment_date_time->format('l, F j, Y');

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting('Appointment Cancelled')
            ->line("Your appointment on {$date} has been cancelled.")
            ->when($this->reason, function($message) {
                return $message->line("**Reason:** {$this->reason}");
            })
            ->action('Reschedule', route('appointments.create'))
            ->line('Contact us if you have any questions.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Appointment Cancelled',
            'message' => 'Your appointment has been cancelled',
            'type' => 'appointment',
            'reason' => $this->reason,
        ];
    }
}
