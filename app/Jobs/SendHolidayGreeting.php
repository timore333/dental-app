<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * SendHolidayGreeting Job
 * Scheduled job to send holiday greetings to all active patients
 * Runs daily and checks for holidays
 */
class SendHolidayGreeting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum retry attempts
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Timeout in seconds
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Holiday to send greeting for
     *
     * @var string
     */
    protected $holiday;

    /**
     * Create a new job instance.
     *
     * @param string|null $holiday
     */
    public function __construct($holiday = null)
    {
        $this->holiday = $holiday;
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendHolidayGreeting job started', ['holiday' => $this->holiday]);

            // Get holiday for today if not specified
            $holiday = $this->holiday ?? $this->getTodayHoliday();

            if (!$holiday) {
                Log::info('No holiday today');
                return;
            }

            // Get all active patients
            $patients = Patient::whereHas('user', function ($query) {
                $query->where('is_active', true);
            })->with('user')->get();

            if ($patients->isEmpty()) {
                Log::info('No active patients found');
                return;
            }

            Log::info("Found {$patients->count()} active patients for holiday greeting", [
                'holiday' => $holiday,
            ]);

            $smsService = app('App\Services\SmsService');
            $emailService = app('App\Services\EmailService');
            $notificationService = app('App\Services\NotificationService');

            foreach ($patients as $patient) {
                try {
                    $this->sendHolidayGreeting(
                        $patient,
                        $holiday,
                        $smsService,
                        $emailService,
                        $notificationService
                    );
                } catch (Exception $e) {
                    Log::error('Failed to send holiday greeting', [
                        'patient_id' => $patient->id,
                        'holiday' => $holiday,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('SendHolidayGreeting job completed', ['holiday' => $holiday]);

        } catch (Exception $e) {
            Log::error('SendHolidayGreeting job error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get today's holiday name
     *
     * @return string|null
     */
    protected function getTodayHoliday()
    {
        $today = now();
        $month = $today->month;
        $day = $today->day;

        // Define holidays (Egyptian holidays)
        $holidays = [
            '1-1' => 'New Year',
            '1-25' => 'Revolution Day',
            '4-25' => 'Sinai Liberation Day',
            '5-1' => 'Labour Day',
            '6-30' => 'June 30th Revolution',
            '7-23' => 'July Revolution',
            '9-6' => 'Police Day',
            '10-6' => 'Armed Forces Day',
        ];

        $key = "{$month}-{$day}";
        return $holidays[$key] ?? null;
    }

    /**
     * Send holiday greeting to patient
     *
     * @param Patient $patient
     * @param string $holiday
     * @param mixed $smsService
     * @param mixed $emailService
     * @param mixed $notificationService
     * @return void
     */
    protected function sendHolidayGreeting(
        $patient,
        $holiday,
        $smsService,
        $emailService,
        $notificationService
    ) {
        $user = $patient->user;
        if (!$user) {
            Log::warning('Patient has no user', ['patient_id' => $patient->id]);
            return;
        }

        // Check user preferences
        $preferences = $user->notificationPreferences;
        if (!$preferences) {
            $preferences = \App\Models\NotificationPreference::create([
                'user_id' => $user->id,
            ]);
        }

        $message = "Happy {$holiday}! 🎉 We wish you a wonderful day. Visit us at Thnaya Clinic for special holiday offers!";
        $subject = "Happy {$holiday}! 🎊";

        // Send SMS
        if ($preferences->isSmsEnabled() && $preferences->promotionalNotificationsEnabled()) {
            if (!$preferences->isQuietHour() && $user->phone) {
                dispatch(new SendSmsNotification(
                    $user->phone,
                    $message,
                    'holiday',
                    auth()->id(),
                    'patient',
                    $patient->id
                ));
            }
        }

        // Send Email
        if ($preferences->isEmailEnabled() && $preferences->promotionalNotificationsEnabled()) {
            dispatch(new SendEmailNotification(
                $user->email,
                $subject,
                'emails.holiday-greeting',
                [
                    'patient' => $patient,
                    'holiday' => $holiday,
                    'message' => $message,
                ],
                'holiday',
                auth()->id(),
                'patient',
                $patient->id
            ));
        }

        // Send In-App Notification
        if ($preferences->isInAppEnabled() && $preferences->promotionalNotificationsEnabled()) {
            $notificationService->createNotification(
                $user->id,
                $subject,
                $message,
                'holiday',
                [
                    'patient_id' => $patient->id,
                    'holiday' => $holiday,
                ]
            );
        }

        Log::info('Holiday greeting sent', [
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'holiday' => $holiday,
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::critical('SendHolidayGreeting job failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
