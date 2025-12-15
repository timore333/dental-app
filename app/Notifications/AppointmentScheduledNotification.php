<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * AppointmentScheduledNotification
 * Sent when appointment is scheduled/confirmed
 */
class AppointmentScheduledNotification extends Notification implements ShouldQueue
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
        if ($preferences && $preferences->isSmsEnabled()) $channels[] = 'sms';
        if ($preferences && $preferences->isEmailEnabled()) $channels[] = 'mail';
        if ($preferences && $preferences->isInAppEnabled()) $channels[] = 'database';
        return $channels ?: ['mail'];
    }

    public function toMail($notifiable)
    {
        $time = $this->appointment->appointment_date_time->format('h:i A');
        $date = $this->appointment->appointment_date_time->format('l, F j, Y');

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting('Appointment Confirmed!')
            ->line("Your appointment has been successfully scheduled.")
            ->line("**Date:** {$date}")
            ->line("**Time:** {$time}")
            ->line("**Clinic:** Thnaya Clinic")
            ->action('View Details', route('appointments.show', $this->appointment))
            ->line('Thank you!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Appointment Confirmed',
            'message' => 'Your appointment has been scheduled successfully',
            'type' => 'appointment',
            'appointment_id' => $this->appointment->id,
        ];
    }
}
