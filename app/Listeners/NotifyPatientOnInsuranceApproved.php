<?php

namespace App\Listeners;

use App\Events\InsuranceApproved;
use App\Jobs\SendSmsNotification;
use App\Jobs\SendEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * NotifyPatientOnInsuranceApproved Listener
 * Sends SMS, email, and in-app notifications when insurance is approved
 */
class NotifyPatientOnInsuranceApproved implements ShouldQueue
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
     * @param InsuranceApproved $event
     * @return void
     */
    public function handle(InsuranceApproved $event)
    {
        try {
            $insurance = $event->insurance;
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

            $approvalDate = now()->format('F j, Y');
            $coverageAmount = number_format($insurance->coverage_amount ?? 0, 2);

            $message = "Good news! Your insurance claim for {$coverageAmount} EGP has been APPROVED! Visit your email for details.";
            $subject = "Insurance Approved - {$coverageAmount} EGP ✅";

            // Send SMS
            if ($preferences->isSmsEnabled() && $user->phone) {
                dispatch(new SendSmsNotification(
                    $user->phone,
                    $message,
                    'insurance_approved',
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
                    'emails.insurance-approved',
                    [
                        'patient' => $patient,
                        'insurance' => $insurance,
                        'coverageAmount' => $coverageAmount,
                        'approvalDate' => $approvalDate,
                    ],
                    'insurance_approved',
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
                    'Insurance Approved ✅',
                    $message,
                    'insurance',
                    [
                        'insurance_id' => $insurance->id,
                        'coverage_amount' => $coverageAmount,
                        'approval_date' => $approvalDate,
                        'status' => 'approved',
                    ]
                );
            }

            Log::info('Insurance approval notification sent', [
                'insurance_id' => $insurance->id,
                'user_id' => $user->id,
                'coverage_amount' => $coverageAmount,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to notify patient on insurance approval', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure
     *
     * @param InsuranceApproved $event
     * @param \Throwable $exception
     * @return void
     */
    public function failed(InsuranceApproved $event, \Throwable $exception)
    {
        Log::error('NotifyPatientOnInsuranceApproved listener failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
