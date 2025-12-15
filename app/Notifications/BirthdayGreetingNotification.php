<?php

namespace App\Notifications;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BirthdayGreetingNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $patient;
    public $age;

    public function __construct(Patient $patient, $age = null)
    {
        $this->patient = $patient;
        $this->age = $age ?? $patient->date_of_birth->diffInYears(now());
    }

    public function via($notifiable)
    {
        $preferences = $notifiable->notificationPreferences;
        $channels = [];
        if ($preferences && $preferences->isSmsEnabled() && $preferences->promotionalNotificationsEnabled()) $channels[] = 'sms';
        if ($preferences && $preferences->isEmailEnabled() && $preferences->promotionalNotificationsEnabled()) $channels[] = 'mail';
        if ($preferences && $preferences->isInAppEnabled() && $preferences->promotionalNotificationsEnabled()) $channels[] = 'database';
        return $channels ?: ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->greeting('🎂 Happy Birthday!')
            ->line("Dear {$this->patient->first_name},")
            ->line("We wish you a wonderful birthday and a great year ahead!")
            ->line("As a special gift, enjoy an exclusive discount on your next visit to Thnaya Clinic.")
            ->action('Claim Your Offer', route('offers.birthday'))
            ->line('Thank you for being our valued patient!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '🎂 Happy Birthday!',
            'message' => "We wish you a wonderful birthday, {$this->patient->first_name}! Enjoy our special birthday offer.",
            'type' => 'birthday',
            'patient_id' => $this->patient->id,
            'age' => $this->age,
        ];
    }
}
