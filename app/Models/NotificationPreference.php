<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * NotificationPreference Model
 * Stores user's notification preferences
 * Allows users to customize what notifications they receive and how
 */
class NotificationPreference extends Model
{
    use HasFactory;

    protected $table = 'notification_preferences';

    protected $fillable = [
        'user_id',
        'sms_enabled',
        'email_enabled',
        'in_app_enabled',
        'appointment_reminders',
        'payment_notifications',
        'insurance_notifications',
        'promotional_notifications',
        'marketing_sms',
        'quiet_hours_start',
        'quiet_hours_end',
        'email_frequency',
    ];

    protected $casts = [
        'sms_enabled' => 'boolean',
        'email_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
        'appointment_reminders' => 'boolean',
        'payment_notifications' => 'boolean',
        'insurance_notifications' => 'boolean',
        'promotional_notifications' => 'boolean',
        'marketing_sms' => 'boolean',
        'quiet_hours_start' => 'string',
        'quiet_hours_end' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user these preferences belong to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if SMS notifications are enabled
     *
     * @return bool
     */
    public function isSmsEnabled()
    {
        return $this->sms_enabled === true;
    }

    /**
     * Check if email notifications are enabled
     *
     * @return bool
     */
    public function isEmailEnabled()
    {
        return $this->email_enabled === true;
    }

    /**
     * Check if in-app notifications are enabled
     *
     * @return bool
     */
    public function isInAppEnabled()
    {
        return $this->in_app_enabled === true;
    }

    /**
     * Check if appointment reminders are enabled
     *
     * @return bool
     */
    public function appointmentRemindersEnabled()
    {
        return $this->appointment_reminders === true;
    }

    /**
     * Check if payment notifications are enabled
     *
     * @return bool
     */
    public function paymentNotificationsEnabled()
    {
        return $this->payment_notifications === true;
    }

    /**
     * Check if insurance notifications are enabled
     *
     * @return bool
     */
    public function insuranceNotificationsEnabled()
    {
        return $this->insurance_notifications === true;
    }

    /**
     * Check if promotional notifications are enabled
     *
     * @return bool
     */
    public function promotionalNotificationsEnabled()
    {
        return $this->promotional_notifications === true;
    }

    /**
     * Check if marketing SMS are enabled
     *
     * @return bool
     */
    public function marketingSmsEnabled()
    {
        return $this->marketing_sms === true;
    }

    /**
     * Check if current time is within quiet hours
     *
     * @return bool
     */
    public function isQuietHour()
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now();
        $start = \Carbon\Carbon::createFromTimeString($this->quiet_hours_start);
        $end = \Carbon\Carbon::createFromTimeString($this->quiet_hours_end);

        // Handle case where quiet hours span midnight
        if ($start > $end) {
            return $now->greaterThanOrEqualTo($start) || $now->lessThan($end);
        }

        return $now->greaterThanOrEqualTo($start) && $now->lessThan($end);
    }

    /**
     * Get quiet hours as formatted string
     *
     * @return string
     */
    public function getQuietHoursLabel()
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return 'Not set';
        }

        return "{$this->quiet_hours_start} - {$this->quiet_hours_end}";
    }

    /**
     * Get email frequency label
     *
     * @return string
     */
    public function getEmailFrequencyLabel()
    {
        $frequencies = [
            'immediately' => 'Immediately',
            'daily' => 'Daily Digest',
            'weekly' => 'Weekly Digest',
            'never' => 'Never',
        ];

        return $frequencies[$this->email_frequency] ?? 'Unknown';
    }

    /**
     * Check if should send notification based on preferences and quiet hours
     *
     * @param string $channel Channel (sms, email, in_app)
     * @param string $type Notification type
     * @return bool
     */
    public function shouldSend($channel, $type = null)
    {
        // Check if channel is enabled
        if ($channel === 'sms' && !$this->isSmsEnabled()) {
            return false;
        }

        if ($channel === 'email' && !$this->isEmailEnabled()) {
            return false;
        }

        if ($channel === 'in_app' && !$this->isInAppEnabled()) {
            return false;
        }

        // Check if type is enabled
        if ($type) {
            switch ($type) {
                case 'appointment':
                    return $this->appointmentRemindersEnabled();
                case 'payment':
                    return $this->paymentNotificationsEnabled();
                case 'insurance':
                    return $this->insuranceNotificationsEnabled();
                case 'promotional':
                    return $this->promotionalNotificationsEnabled();
                case 'marketing':
                    return $this->marketingSmsEnabled();
            }
        }

        // Don't send SMS during quiet hours (but allow other channels)
        if ($channel === 'sms' && $this->isQuietHour()) {
            return false;
        }

        return true;
    }

    /**
     * Get all enabled notification types
     *
     * @return array
     */
    public function getEnabledTypes()
    {
        $types = [];

        if ($this->appointmentRemindersEnabled()) {
            $types[] = 'appointment';
        }

        if ($this->paymentNotificationsEnabled()) {
            $types[] = 'payment';
        }

        if ($this->insuranceNotificationsEnabled()) {
            $types[] = 'insurance';
        }

        if ($this->promotionalNotificationsEnabled()) {
            $types[] = 'promotional';
        }

        if ($this->marketingSmsEnabled()) {
            $types[] = 'marketing';
        }

        return $types;
    }

    /**
     * Get all enabled channels
     *
     * @return array
     */
    public function getEnabledChannels()
    {
        $channels = [];

        if ($this->isSmsEnabled()) {
            $channels[] = 'sms';
        }

        if ($this->isEmailEnabled()) {
            $channels[] = 'email';
        }

        if ($this->isInAppEnabled()) {
            $channels[] = 'in_app';
        }

        return $channels;
    }

    /**
     * Reset all preferences to defaults
     *
     * @return void
     */
    public function resetToDefaults()
    {
        $this->update([
            'sms_enabled' => true,
            'email_enabled' => true,
            'in_app_enabled' => true,
            'appointment_reminders' => true,
            'payment_notifications' => true,
            'insurance_notifications' => true,
            'promotional_notifications' => false,
            'marketing_sms' => false,
            'quiet_hours_start' => null,
            'quiet_hours_end' => null,
            'email_frequency' => 'immediately',
        ]);
    }

    /**
     * Get preference summary as formatted string
     *
     * @return string
     */
    public function getSummary()
    {
        $summary = [];

        $channels = [];
        if ($this->isSmsEnabled()) $channels[] = 'SMS';
        if ($this->isEmailEnabled()) $channels[] = 'Email';
        if ($this->isInAppEnabled()) $channels[] = 'In-App';

        $summary[] = 'Channels: ' . (count($channels) ? implode(', ', $channels) : 'None');

        $types = $this->getEnabledTypes();
        $summary[] = 'Types: ' . (count($types) ? count($types) . ' enabled' : 'None');

        if ($this->quiet_hours_start && $this->quiet_hours_end) {
            $summary[] = 'Quiet hours: ' . $this->getQuietHoursLabel();
        }

        $summary[] = 'Email frequency: ' . $this->getEmailFrequencyLabel();

        return implode(' | ', $summary);
    }
}
