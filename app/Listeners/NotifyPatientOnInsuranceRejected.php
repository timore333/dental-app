<?php

namespace App\Listeners;

use App\Events\InsuranceRejected;
use App\Jobs\SendSmsNotification;
use App\Jobs\SendEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * NotifyPatientOnInsuranceRejected Listener
 * Sends SMS, email, and in-app notifications when insurance is rejected
 */
class NotifyPatientOnInsuranceRejected implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The time to wait before retrying the job
     *
     * @var int
     */
    public $delay = 0;

    /**
     * The number of times the queued listener may be attempted
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Handle the event.
     *
     * @param InsuranceRejected $event
     * @return void
     */
    public function handle(InsuranceRejected $event)
    {
        try {
            $insurance = $event->insurance;
            $reason = $event->reason ?? 'Please contact us for more information.';
            $patient = $insurance->patient;
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

            if (!$preferences->insuranceNotificationsEnabled()) {
                Log::info('Insurance notifications disabled', ['user_id' => $user->id]);
                return;
            }

            $rejectionDate = now()->format('F j, Y');

            $message = "Your insurance claim has been REJECTED. Reason: {$reason} Please contact us at Thnaya Clinic for support.";
            $subject = "Insurance Claim Rejected ❌";

            // Send SMS
            if ($preferences->isSmsEnabled() && $user->phone) {
                dispatch(new SendSmsNotification(
                    $user->phone,
                    $message,
                    'insurance_rejected',
                    auth()->id(),
                    'insurance',
                    $insurance->id
                ));
            }

            // Send Email
            if ($preferences->isEmailEnabled()) {
                dispatch(new SendEmailNotification(
                    $user->email,
                    $subject,
                    'emails.insurance-rejected',
                    [
                        'patient' => $patient,
                        'insurance' => $insurance,
                        'reason' => $reason,
                        'rejectionDate' => $rejectionDate,
                    ],
                    'insurance_rejected',
                    auth()->id(),
                    'insurance',
                    $insurance->id
                ));
            }

            // Send In-App Notification
            if ($preferences->isInAppEnabled()) {
                $notificationService = app('App\Services\NotificationService');
                $notificationService->createNotification(
                    $user->id,
                    'Insurance Claim Rejected',
                    $message,
                    'insurance',
                    [
                        'insurance_id' => $insurance->id,
                        'reason' => $reason,
                        'rejection_date' => $rejectionDate,
                        'status' => 'rejected',
                    ]
                );
            }

            Log::info('Insurance rejection notification sent', [
                'insurance_id' => $insurance->id,
                'user_id' => $user->id,
                'reason' => $reason,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to notify patient on insurance rejection', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure
     *
     * @param InsuranceRejected $event
     * @param \Throwable $exception
     * @return void
     */
    public function failed(InsuranceRejected $event, \Throwable $exception)
    {
        Log::error('NotifyPatientOnInsuranceRejected listener failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
