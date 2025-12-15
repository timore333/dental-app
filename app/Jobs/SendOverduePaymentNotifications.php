<?php

namespace App\Jobs;

use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * SendOverduePaymentNotifications Job
 * Scheduled job to send payment overdue notifications
 * Runs daily at 2 PM and sends reminders for overdue bills
 */
class SendOverduePaymentNotifications implements ShouldQueue
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
            Log::info('SendOverduePaymentNotifications job started');

            // Get overdue bills
            $overduePayments = $this->getOverduePayments();

            if ($overduePayments->isEmpty()) {
                Log::info('No overdue payments found');
                return;
            }

            Log::info("Found {$overduePayments->count()} overdue payments");

            $smsService = app('App\Services\SmsService');
            $emailService = app('App\Services\EmailService');
            $notificationService = app('App\Services\NotificationService');

            foreach ($overduePayments as $payment) {
                try {
                    $this->sendOverdueNotification(
                        $payment,
                        $smsService,
                        $emailService,
                        $notificationService
                    );
                } catch (Exception $e) {
                    Log::error('Failed to send overdue payment notification', [
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('SendOverduePaymentNotifications job completed');

        } catch (Exception $e) {
            Log::error('SendOverduePaymentNotifications job error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get overdue payments (due date passed, status not paid)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getOverduePayments()
    {
        return Payment::where('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'paid')
            ->where('notification_sent', false)
            ->with('patient', 'patient.user')
            ->get();
    }

    /**
     * Send overdue payment notification
     *
     * @param Payment $payment
     * @param mixed $smsService
     * @param mixed $emailService
     * @param mixed $notificationService
     * @return void
     */
    protected function sendOverdueNotification(
        $payment,
        $smsService,
        $emailService,
        $notificationService
    ) {
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
            Log::info('Payment notifications disabled for user', ['user_id' => $user->id]);
            return;
        }

        $daysOverdue = now()->diffInDays($payment->due_date);
        $amount = number_format($payment->amount, 2);

        $message = "Payment Overdue: Your bill of {$amount} EGP is now {$daysOverdue} days overdue. Please pay as soon as possible. Visit Thnaya Clinic or call us for payment options.";
        $subject = "Payment Overdue - {$amount} EGP";

        // Send SMS
        if ($preferences->isSmsEnabled() && !$preferences->isQuietHour() && $user->phone) {
            dispatch(new SendSmsNotification(
                $user->phone,
                $message,
                'overdue_payment',
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
                'emails.overdue-payment',
                [
                    'patient' => $patient,
                    'payment' => $payment,
                    'daysOverdue' => $daysOverdue,
                    'amount' => $amount,
                    'message' => $message,
                ],
                'overdue_payment',
                auth()->id(),
                'payment',
                $payment->id
            ));
        }

        // Send In-App Notification
        if ($preferences->isInAppEnabled()) {
            $notificationService->createNotification(
                $user->id,
                'Payment Overdue',
                $message,
                'payment',
                [
                    'payment_id' => $payment->id,
                    'amount' => $amount,
                    'days_overdue' => $daysOverdue,
                ]
            );
        }

        // Mark notification as sent
        $payment->update(['notification_sent' => true]);

        Log::info('Overdue payment notification sent', [
            'payment_id' => $payment->id,
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'amount' => $amount,
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
        Log::critical('SendOverduePaymentNotifications job failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
