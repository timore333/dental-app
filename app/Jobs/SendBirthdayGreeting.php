<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Patient;
use App\Services\SmsService;
use App\Services\EmailService;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * SendBirthdayGreeting Job
 * Scheduled job to send birthday greetings to patients
 * Runs daily and sends SMS/Email/In-App notifications to patients with birthdays
 */
class SendBirthdayGreeting implements ShouldQueue
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
     * Create a new job instance.
     */
    public function __construct()
    {
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
            Log::info('SendBirthdayGreeting job started');

            // Get patients with birthdays today
            $patients = $this->getPatientsWithBirthdayToday();

            if ($patients->isEmpty()) {
                Log::info('No patients with birthdays today');
                return;
            }

            Log::info("Found {$patients->count()} patients with birthdays");

            $smsService = app('App\Services\SmsService');
            $emailService = app('App\Services\EmailService');
            $notificationService = app('App\Services\NotificationService');

            foreach ($patients as $patient) {
                try {
                    $this->sendBirthdayGreeting(
                        $patient,
                        $smsService,
                        $emailService,
                        $notificationService
                    );
                } catch (Exception $e) {
                    Log::error('Failed to send birthday greeting', [
                        'patient_id' => $patient->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('SendBirthdayGreeting job completed');

        } catch (Exception $e) {
            Log::error('SendBirthdayGreeting job error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get patients with birthdays today
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPatientsWithBirthdayToday()
    {
        $today = now();

        return Patient::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->with('user')
            ->get();
    }

    /**
     * Send birthday greeting to patient
     *
     * @param Patient $patient
     * @param SmsService $smsService
     * @param EmailService $emailService
     * @param NotificationService $notificationService
     * @return void
     */
    protected function sendBirthdayGreeting(
        $patient,
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

        // Calculate age
        $age = now()->diffInYears($patient->date_of_birth);

        $message = "Happy Birthday! 🎂 We hope you have a wonderful day. Come visit us at Thnaya Clinic for a special birthday offer!";
        $subject = "Happy Birthday! 🎉";

        // Send SMS
        if ($preferences->isSmsEnabled() && $preferences->promotionalNotificationsEnabled()) {
            if (!$preferences->isQuietHour() && $user->phone) {
                dispatch(new SendSmsNotification(
                    $user->phone,
                    $message,
                    'birthday',
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
                'emails.birthday-greeting',
                [
                    'patient' => $patient,
                    'age' => $age,
                    'message' => $message,
                ],
                'birthday',
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
                'birthday',
                [
                    'patient_id' => $patient->id,
                    'age' => $age,
                ]
            );
        }

        Log::info('Birthday greeting sent', [
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'age' => $age,
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
        Log::critical('SendBirthdayGreeting job failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
