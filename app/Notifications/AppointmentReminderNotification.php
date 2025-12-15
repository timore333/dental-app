<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * AppointmentReminderNotification
 * Sent 24 hours before appointment
 */
class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        $preferences = $notifiable->notificationPreferences;
        $channels = [];

        if ($preferences && $preferences->isSmsEnabled() && !$preferences->isQuietHour()) {
            $channels[] = 'sms';
        }
        if ($preferences && $preferences->isEmailEnabled()) {
            $channels[] = 'mail';
        }
        if ($preferences && $preferences->isInAppEnabled()) {
            $channels[] = 'database';
        }

        return $channels ?: ['mail'];
    }

    public function toMail($notifiable)
    {
        $time = $this->appointment->appointment_date_time->format('h:i A');
        $date = $this->appointment->appointment_date_time->format('l, F j, Y');

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting("Appointment Reminder!")
            ->line("Your appointment at Thnaya Clinic is scheduled for:")
            ->line("**{$date} at {$time}**")
            ->line("Please arrive 10 minutes early.")
            ->action('View Appointment', route('appointments.show', $this->appointment))
            ->line("If you need to reschedule, please contact us as soon as possible.")
            ->salutation('Thnaya Clinic Team');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Appointment Reminder',
            'message' => 'Your appointment is scheduled for tomorrow at ' . $this->appointment->appointment_date_time->format('h:i A'),
            'type' => 'appointment',
            'appointment_id' => $this->appointment->id,
            'url' => route('appointments.show', $this->appointment),
        ];
    }
}
