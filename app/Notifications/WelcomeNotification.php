<?php

namespace App\Notifications;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * WelcomeNotification
 * Sent when a new patient registers
 */
class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Patient instance
     *
     * @var Patient
     */
    public $patient;

    /**
     * Create a new notification instance.
     *
     * @param Patient $patient
     */
    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $preferences = $notifiable->notificationPreferences;
        $channels = [];

        if ($preferences && $preferences->isSmsEnabled()) {
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

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting("Welcome {$this->patient->first_name}!")
            ->line('Welcome to Thnaya Clinic! We are delighted to have you as a patient.')
            ->line('Your account has been set up and you can now:')
            ->line('• Book appointments online')
            ->line('• View your medical records')
            ->line('• Receive appointment reminders')
            ->line('• Track your billing and payments')
            ->action('Login to Your Account', route('dashboard'))
            ->line('If you have any questions, please do not hesitate to contact us.')
            ->salutation('Best regards, Thnaya Clinic Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Welcome to Thnaya Clinic!',
            'message' => 'Welcome ' . $this->patient->first_name . '! Your account is ready.',
            'type' => 'welcome',
            'patient_id' => $this->patient->id,
            'url' => route('dashboard'),
        ];
    }
}
