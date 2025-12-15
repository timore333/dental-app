<?php

namespace App\Livewire\Settings;

use App\Models\NotificationPreference;
use Livewire\Component;

/**
 * NotificationPreferences Component
 * Manages user notification preferences
 */
class NotificationPreferences extends Component
{
    /**
     * SMS enabled
     *
     * @var bool
     */
    public $smsEnabled = true;

    /**
     * Email enabled
     *
     * @var bool
     */
    public $emailEnabled = true;

    /**
     * In-app enabled
     *
     * @var bool
     */
    public $inAppEnabled = true;

    /**
     * Appointment reminders
     *
     * @var bool
     */
    public $appointmentReminders = true;

    /**
     * Payment notifications
     *
     * @var bool
     */
    public $paymentNotifications = true;

    /**
     * Insurance notifications
     *
     * @var bool
     */
    public $insuranceNotifications = true;

    /**
     * Promotional notifications
     *
     * @var bool
     */
    public $promotionalNotifications = false;

    /**
     * Marketing SMS
     *
     * @var bool
     */
    public $marketingSms = false;

    /**
     * Quiet hours start
     *
     * @var string|null
     */
    public $quietHoursStart = null;

    /**
     * Quiet hours end
     *
     * @var string|null
     */
    public $quietHoursEnd = null;

    /**
     * Email frequency
     *
     * @var string
     */
    public $emailFrequency = 'immediately';

    /**
     * Success message
     *
     * @var string|null
     */
    public $successMessage = null;

    /**
     * Mount the component
     *
     * @return void
     */
    public function mount()
    {
        $user = auth()->user();
        $preferences = $user->notificationPreferences ?? NotificationPreference::create([
            'user_id' => $user->id,
        ]);

        $this->smsEnabled = $preferences->sms_enabled;
        $this->emailEnabled = $preferences->email_enabled;
        $this->inAppEnabled = $preferences->in_app_enabled;
        $this->appointmentReminders = $preferences->appointment_reminders;
        $this->paymentNotifications = $preferences->payment_notifications;
        $this->insuranceNotifications = $preferences->insurance_notifications;
        $this->promotionalNotifications = $preferences->promotional_notifications;
        $this->marketingSms = $preferences->marketing_sms;
        $this->quietHoursStart = $preferences->quiet_hours_start;
        $this->quietHoursEnd = $preferences->quiet_hours_end;
        $this->emailFrequency = $preferences->email_frequency;
    }

    /**
     * Save preferences
     *
     * @return void
     */
    public function save()
    {
        try {
            $user = auth()->user();
            $preferences = $user->notificationPreferences;

            $preferences->update([
                'sms_enabled' => $this->smsEnabled,
                'email_enabled' => $this->emailEnabled,
                'in_app_enabled' => $this->inAppEnabled,
                'appointment_reminders' => $this->appointmentReminders,
                'payment_notifications' => $this->paymentNotifications,
                'insurance_notifications' => $this->insuranceNotifications,
                'promotional_notifications' => $this->promotionalNotifications,
                'marketing_sms' => $this->marketingSms,
                'quiet_hours_start' => $this->quietHoursStart,
                'quiet_hours_end' => $this->quietHoursEnd,
                'email_frequency' => $this->emailFrequency,
            ]);

            $this->successMessage = 'Notification preferences saved successfully!';
            $this->dispatch('preferencesSaved');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to save preferences');
        }
    }

    /**
     * Reset to defaults
     *
     * @return void
     */
    public function resetToDefaults()
    {
        try {
            $user = auth()->user();
            $preferences = $user->notificationPreferences;
            $preferences->resetToDefaults();

            $this->mount();
            $this->successMessage = 'Preferences reset to defaults!';
            $this->dispatch('preferencesReset');
        } catch (\Exception $e) {
            $this->dispatch('error', 'Failed to reset preferences');
        }
    }

    /**
     * Disable all
     *
     * @return void
     */
    public function disableAll()
    {
        $this->smsEnabled = false;
        $this->emailEnabled = false;
        $this->inAppEnabled = false;
        $this->appointmentReminders = false;
        $this->paymentNotifications = false;
        $this->insuranceNotifications = false;
        $this->promotionalNotifications = false;
        $this->marketingSms = false;
    }

    /**
     * Enable all
     *
     * @return void
     */
    public function enableAll()
    {
        $this->smsEnabled = true;
        $this->emailEnabled = true;
        $this->inAppEnabled = true;
        $this->appointmentReminders = true;
        $this->paymentNotifications = true;
        $this->insuranceNotifications = true;
        $this->promotionalNotifications = true;
        $this->marketingSms = true;
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.settings.notification-preferences');
    }
}
