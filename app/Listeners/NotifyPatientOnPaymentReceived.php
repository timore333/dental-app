<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Jobs\SendSmsNotification;
use App\Jobs\SendEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * NotifyPatientOnPaymentReceived Listener
 * Sends SMS, email, and in-app notifications when payment is received
 */
class NotifyPatientOnPaymentReceived implements ShouldQueue
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
     * @param PaymentReceived $event
     * @return void
     */
    public function handle(PaymentReceived $event)
    {
        try {
            $payment = $event->payment;
            $patient = $payment->patient;
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

            if (!$preferences->paymentNotificationsEnabled()) {
                Log::info('Payment notifications disabled', ['user_id' => $user->id]);
                return;
            }

            $amount = number_format($payment->amount, 2);
            $paymentDate = $payment->paid_date->format('F j, Y');

            $message = "Thank you! We have received your payment of {$amount} EGP. Receipt has been sent to your email.";
            $subject = "Payment Received - {$amount} EGP";

            // Send SMS
            if ($preferences->isSmsEnabled() && $user->phone) {
                dispatch(new SendSmsNotification(
                    $user->phone,
                    $message,
                    'payment_received',
                    auth()->id(),
                    'payment',
                    $payment->id
                ));
            }

            // Send Email
            if ($preferences->isEmailEnabled()) {
                dispatch(new SendEmailNotification(
                    $user->email,
                    $subject,
                    'emails.payment-receipt',
                    [
                        'patient' => $patient,
                        'payment' => $payment,
                        'amount' => $amount,
                        'paymentDate' => $paymentDate,
                    ],
                    'payment_received',
                    auth()->id(),
                    'payment',
                    $payment->id
                ));
            }

            // Send In-App Notification
            if ($preferences->isInAppEnabled()) {
                $notificationService = app('App\Services\NotificationService');
                $notificationService->createNotification(
                    $user->id,
                    'Payment Received',
                    $message,
                    'payment',
                    [
                        'payment_id' => $payment->id,
                        'amount' => $amount,
                        'payment_date' => $paymentDate,
                    ]
                );
            }

            Log::info('Payment received notification sent', [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'amount' => $amount,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to notify patient on payment received', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure
     *
     * @param PaymentReceived $event
     * @param \Throwable $exception
     * @return void
     */
    public function failed(PaymentReceived $event, \Throwable $exception)
    {
        Log::error('NotifyPatientOnPaymentReceived listener failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
