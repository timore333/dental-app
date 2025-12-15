<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateNotificationPreferencesRequest
 * Validates notification preference updates
 */
class UpdateNotificationPreferencesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sms_enabled' => 'boolean',
            'email_enabled' => 'boolean',
            'in_app_enabled' => 'boolean',
            'appointment_reminders' => 'boolean',
            'payment_notifications' => 'boolean',
            'insurance_notifications' => 'boolean',
            'promotional_notifications' => 'boolean',
            'marketing_sms' => 'boolean',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
            'email_frequency' => 'in:immediately,daily,weekly,never',
        ];
    }

    /**
     * Get custom error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'quiet_hours_start.date_format' => 'Quiet hours start must be a valid time (HH:mm)',
            'quiet_hours_end.date_format' => 'Quiet hours end must be a valid time (HH:mm)',
            'email_frequency.in' => 'Email frequency must be one of: immediately, daily, weekly, never',
        ];
    }
}
