<?php

return [

    /*
     * Default notification channels enabled for all notification types
     * Can be overridden per user in notification_preferences table
     */
    'default_channels' => ['mail', 'sms', 'database'],

    /*
     * SMS Configuration
     */
    'sms' => [
        'enabled' => env('SMS_ENABLED', true),
        'driver' => env('SMS_DRIVER', 'epush'),
        'sender_id' => env('EPUSH_SENDER_ID', 'Thnaya'),

        // Rate limiting
        'rate_limiting' => [
            'enabled' => true,
            'max_per_phone_per_day' => env('SMS_RATE_LIMIT_PER_DAY', 10),
            'max_per_phone_per_hour' => env('SMS_RATE_LIMIT_PER_HOUR', 3),
            'check_window_hours' => 24,
        ],

        // Retry policy
        'retry' => [
            'max_attempts' => 3,
            'backoff_seconds' => [1, 5, 15],
            'timeout' => 30,
        ],

        // Queue configuration
        'queue' => [
            'enabled' => true,
            'connection' => 'database',
            'queue' => 'sms',
        ],
    ],

    /*
     * Email Configuration
     */
    'email' => [
        'enabled' => env('MAIL_ENABLED', true),
        'driver' => env('MAIL_DRIVER', 'mailtrap'),

        // Retry policy
        'retry' => [
            'max_attempts' => 3,
            'timeout' => 60,
        ],

        // Queue configuration
        'queue' => [
            'enabled' => true,
            'connection' => 'database',
            'queue' => 'notifications',
        ],
    ],

    /*
     * In-App Database Notifications
     */
    'database' => [
        'enabled' => true,
        'retention_days' => 30, // Auto-cleanup old notifications
    ],

    /*
     * Scheduled Jobs Configuration
     */
    'scheduled_jobs' => [
        // Birthday greetings - daily at 8 AM
        'birthday_greeting' => [
            'enabled' => true,
            'time' => '08:00',
            'timezone' => 'Africa/Cairo',
        ],

        // Appointment reminders - daily at 10 AM (24 hours before)
        'appointment_reminder' => [
            'enabled' => true,
            'time' => '10:00',
            'hours_before' => 24,
        ],

        // Overdue payment notifications - daily at 2 PM
        'overdue_payment' => [
            'enabled' => true,
            'time' => '14:00',
            'days_overdue' => 7,
            'repeat_every_days' => 7, // Don't spam, repeat weekly
        ],

        // Holiday greetings - daily at 8 AM
        'holiday_greeting' => [
            'enabled' => true,
            'time' => '08:00',
        ],
    ],

    /*
     * Holiday Dates for automatic greetings
     * Format: YYYY-MM-DD => 'Holiday Name'
     */
    'holidays' => [
        '2025-01-25' => 'Egyptian Revolution Day',
        '2025-04-25' => 'Sinai Liberation Day',
        '2025-05-01' => 'Labour Day',
        '2025-06-30' => 'June 30 Revolution',
        '2025-07-07' => 'Islamic New Year',
        '2025-07-23' => 'July 23 Revolution',
        '2025-09-23' => 'Eid Al-Adha',
        '2025-10-06' => 'Armed Forces Day',
        '2025-12-25' => 'Christmas',
    ],

    /*
     * Islamic Holidays (Variable dates - check annually)
     * Update based on Hijri calendar
     */
    'islamic_holidays' => [
        '2025-07-07' => 'Eid Al-Adha',
        '2025-09-23' => 'Eid Al-Fitr',
    ],

    /*
     * Notification Templates & Messages
     * Can be customized per clinic needs
     */
    'messages' => [
        'welcome_sms' => 'Welcome to Thnaya Clinic! Patient file #{file_number}. Call {clinic_phone} to book appointment.',

        'appointment_reminder_sms' => 'Reminder: Appointment tomorrow at {time} with Dr. {doctor_name}. Call {clinic_phone} to reschedule.',

        'appointment_scheduled_sms' => 'Appointment scheduled for {date} at {time} with Dr. {doctor_name}. Thank you!',

        'appointment_cancelled_sms' => 'Your appointment on {date} has been cancelled. Call {clinic_phone} to reschedule.',

        'payment_received_sms' => 'Payment received: {amount} EGP. Receipt #{receipt_number}. Thank you!',

        'insurance_approved_sms' => '{insurance_company} approved {amount} EGP for your treatment.',

        'insurance_rejected_sms' => '{insurance_company} response received. Contact clinic for details.',

        'birthday_greeting_sms' => 'Happy Birthday {patient_name}! Enjoy 10% discount on your next visit at Thnaya Clinic.',

        'overdue_payment_sms' => 'Outstanding bill: {amount} EGP. Due date: {due_date}. Call {clinic_phone} to pay.',
    ],

    /*
     * Notification Preferences - what users can control
     */
    'user_preferences' => [
        'sms_enabled' => true,
        'email_enabled' => true,
        'in_app_enabled' => true,
        'appointment_reminders' => true,
        'payment_notifications' => true,
        'insurance_notifications' => true,
        'promotional_notifications' => false, // Patients opt-in for promotions
        'marketing_sms' => false,
    ],

    /*
     * Notification Types for categorization
     */
    'notification_types' => [
        'appointment' => 'Appointment',
        'payment' => 'Payment',
        'insurance' => 'Insurance',
        'welcome' => 'Welcome',
        'reminder' => 'Reminder',
        'birthday' => 'Birthday',
        'holiday' => 'Holiday Greeting',
        'overdue' => 'Payment Overdue',
        'system' => 'System',
        'warning' => 'Warning',
    ],

];
