<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sendgrid' => [
        'secret' => env('SENDGRID_API_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
        ],
    ],

    /**
     * E-PUSH EGYPT SMS CONFIGURATION
     * Vodafone SMS API for Egyptian market
     *
     * Credentials obtained from https://epushagency.eg/
     */
    'epush' => [
        'username' => env('EPUSH_USERNAME', ''),
        'password' => env('EPUSH_PASSWORD', ''),
        'api_key' => env('EPUSH_API_KEY', ''),
        'sender_id' => env('EPUSH_SENDER_ID', 'Thnaya'),
        'endpoint' => env('EPUSH_ENDPOINT', 'https://api.epusheg.com/api/v2/send_bulk'),
        'timeout' => env('EPUSH_TIMEOUT', 30),
        'max_retries' => env('EPUSH_MAX_RETRIES', 3),
        'retry_delay' => env('EPUSH_RETRY_DELAY', 5), // seconds
    ],

    /**
     * SMS SERVICE CONFIGURATION
     */
    'sms' => [
        'enabled' => env('SMS_ENABLED', true),
        'driver' => env('SMS_DRIVER', 'epush'),

        // Rate limiting: max SMS per phone per day
        'rate_limit' => [
            'max_per_phone_per_day' => env('SMS_RATE_LIMIT_PER_DAY', 10),
            'max_per_phone_per_hour' => env('SMS_RATE_LIMIT_PER_HOUR', 3),
        ],

        // Message length config
        'message' => [
            'max_length_english' => 918, // Max 6 concatenated messages
            'max_length_arabic' => 402,
            'concatenate_max_messages' => 6,
        ],

        // Queue configuration
        'queue' => [
            'enabled' => env('SMS_QUEUE_ENABLED', true),
            'connection' => env('QUEUE_CONNECTION', 'database'),
            'timeout' => 30, // seconds
            'tries' => 3,
        ],

        // Retry configuration
        'retry' => [
            'enabled' => true,
            'max_attempts' => 3,
            'backoff_seconds' => [1, 5, 15], // exponential backoff
        ],
    ],

    /**
     * EMAIL SERVICE CONFIGURATION
     */
    'mail' => [
        'enabled' => env('MAIL_ENABLED', true),
        'driver' => env('MAIL_DRIVER', 'mailtrap'),
        'queue' => [
            'enabled' => env('MAIL_QUEUE_ENABLED', true),
            'connection' => env('QUEUE_CONNECTION', 'database'),
            'timeout' => 60, // seconds
            'tries' => 3,
        ],
    ],

];
