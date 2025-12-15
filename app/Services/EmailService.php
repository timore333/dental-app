<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\EmailLog;
use App\Jobs\SendEmailNotification;
use Exception;

/**
 * Email Service
 * Handles all email operations including sending, queueing, and logging
 * Supports Mailtrap, Mailgun, SendGrid, and standard SMTP
 */
class EmailService
{
    /**
     * Send email immediately or queue for later
     *
     * @param string $email Email address
     * @param string $subject Email subject
     * @param string $view Blade view path (e.g., 'emails.welcome')
     * @param array $data Data to pass to view
     * @param bool $queued If true, queue the job; if false, send immediately
     * @param int $delaySeconds Delay in seconds before sending
     * @return EmailLog
     */
    public function sendEmail(
        $email,
        $subject,
        $view,
        $data = [],
        $queued = true,
        $delaySeconds = 0
    ) {
        try {
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email address: $email");
            }

            // Determine email type from view name
            $emailType = $this->getEmailType($view);

            // Create log entry
            $log = EmailLog::create([
                'email' => $email,
                'subject' => $subject,
                'email_type' => $emailType,
                'status' => $queued ? 'queued' : 'sent',
                'created_by' => auth()->id(),
            ]);

            if ($queued) {
                // Queue the job
                SendEmailNotification::dispatch($email, $subject, $view, $data, $log->id)
                    ->delay(now()->addSeconds($delaySeconds));
            } else {
                // Send immediately
                $this->sendNow($email, $subject, $view, $data, $log->id);
            }

            return $log;
        } catch (Exception $e) {
            Log::error('Email send failed', [
                'email' => $email ?? 'unknown',
                'subject' => $subject ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send email immediately (synchronously)
     *
     * @param string $email
     * @param string $subject
     * @param string $view
     * @param array $data
     * @param int $logId
     * @return bool
     */
    public function sendNow($email, $subject, $view, $data = [], $logId = null)
    {
        try {
            Mail::send($view, $data, function ($message) use ($email, $subject) {
                $message->to($email)
                    ->subject($subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            // Update log
            if ($logId) {
                $log = EmailLog::find($logId);
                if ($log) {
                    $log->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                }
            }

            Log::info('Email sent successfully', [
                'email' => $email,
                'subject' => $subject,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Email send failed', [
                'email' => $email,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            if ($logId) {
                $log = EmailLog::find($logId);
                if ($log) {
                    $log->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }

            return false;
        }
    }

    /**
     * Send bulk emails to multiple recipients
     *
     * @param array $emails Array of email addresses
     * @param string $subject
     * @param string $view
     * @param array $data
     * @return array Results for each email
     */
    public function sendBulkEmail($emails, $subject, $view, $data = [])
    {
        $results = [];

        foreach ($emails as $email) {
            try {
                $log = $this->sendEmail($email, $subject, $view, $data, queued: true);
                $results[$email] = ['success' => true, 'log_id' => $log->id];
            } catch (Exception $e) {
                $results[$email] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Send receipt email for payment
     *
     * @param object $patient Patient model
     * @param object $payment Payment model
     * @return EmailLog
     */
    public function sendReceipt($patient, $payment)
    {
        return $this->sendEmail(
            $patient->email,
            'Payment Receipt #' . $payment->receipt_number,
            'emails.payment-receipt',
            [
                'patient' => $patient,
                'payment' => $payment,
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send appointment reminder email
     *
     * @param object $appointment Appointment model
     * @param int $hoursBefore Hours before appointment
     * @return EmailLog
     */
    public function sendAppointmentReminder($appointment, $hoursBefore = 24)
    {
        return $this->sendEmail(
            $appointment->patient->email,
            'Appointment Reminder - ' . $appointment->scheduled_at->format('M d, Y'),
            'emails.appointment-reminder',
            [
                'appointment' => $appointment,
                'patient' => $appointment->patient,
                'doctor' => $appointment->doctor,
                'clinic_phone' => config('clinic.phone'),
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send appointment confirmation email
     *
     * @param object $appointment
     * @return EmailLog
     */
    public function sendAppointmentConfirmation($appointment)
    {
        return $this->sendEmail(
            $appointment->patient->email,
            'Appointment Confirmation',
            'emails.appointment-confirmation',
            [
                'appointment' => $appointment,
                'patient' => $appointment->patient,
                'doctor' => $appointment->doctor,
                'clinic_address' => config('clinic.address'),
                'clinic_phone' => config('clinic.phone'),
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send birthday greeting email
     *
     * @param object $patient Patient model
     * @return EmailLog
     */
    public function sendBirthdayGreeting($patient)
    {
        return $this->sendEmail(
            $patient->email,
            'Happy Birthday ' . explode(' ', $patient->name)[0] . '!',
            'emails.birthday-greeting',
            [
                'patient' => $patient,
                'discount_percent' => 10,
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send insurance approval notification email
     *
     * @param object $patient
     * @param object $insurance Insurance approval model
     * @return EmailLog
     */
    public function sendInsuranceApproval($patient, $insurance)
    {
        return $this->sendEmail(
            $patient->email,
            'Insurance Approval - ' . $insurance->insurance_company,
            'emails.insurance-approved',
            [
                'patient' => $patient,
                'insurance' => $insurance,
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send insurance rejection notification email
     *
     * @param object $patient
     * @param object $insurance Insurance response model
     * @return EmailLog
     */
    public function sendInsuranceRejection($patient, $insurance)
    {
        return $this->sendEmail(
            $patient->email,
            'Insurance Response - ' . $insurance->insurance_company,
            'emails.insurance-rejected',
            [
                'patient' => $patient,
                'insurance' => $insurance,
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send overdue payment notification email
     *
     * @param object $patient Patient model
     * @param object $bill Outstanding bill model
     * @return EmailLog
     */
    public function sendOverduePaymentNotice($patient, $bill)
    {
        return $this->sendEmail(
            $patient->email,
            'Outstanding Payment Due',
            'emails.overdue-payment',
            [
                'patient' => $patient,
                'bill' => $bill,
                'clinic_phone' => config('clinic.phone'),
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Send holiday greeting email
     *
     * @param string $email
     * @param string $holiday Holiday name
     * @return EmailLog
     */
    public function sendHolidayGreeting($email, $holiday)
    {
        return $this->sendEmail(
            $email,
            'Happy ' . $holiday,
            'emails.holiday-greeting',
            [
                'holiday_name' => $holiday,
                'clinic_hours' => config('clinic.holiday_hours'),
                'clinic_name' => config('app.name'),
            ]
        );
    }

    /**
     * Queue email for later sending
     *
     * @param string $email
     * @param string $subject
     * @param string $view
     * @param array $data
     * @param int $delaySeconds
     * @return EmailLog
     */
    public function queueEmail($email, $subject, $view, $data = [], $delaySeconds = 0)
    {
        return $this->sendEmail($email, $subject, $view, $data, queued: true, delaySeconds: $delaySeconds);
    }

    /**
     * Get email type from view name
     *
     * @param string $view View path (e.g., 'emails.welcome')
     * @return string Email type
     */
    protected function getEmailType($view)
    {
        $types = [
            'emails.welcome' => 'welcome',
            'emails.appointment-reminder' => 'reminder',
            'emails.appointment-confirmation' => 'appointment',
            'emails.appointment-cancelled' => 'appointment',
            'emails.payment-receipt' => 'receipt',
            'emails.insurance-approved' => 'insurance',
            'emails.insurance-rejected' => 'insurance',
            'emails.birthday-greeting' => 'birthday',
            'emails.holiday-greeting' => 'holiday',
            'emails.overdue-payment' => 'overdue',
        ];

        return $types[$view] ?? 'other';
    }

    /**
     * Log email attempt to audit trail
     *
     * @param string $email
     * @param string $subject
     * @param string $status
     * @param string $error
     * @return EmailLog
     */
    public function logEmailAttempt($email, $subject, $status, $error = null)
    {
        return EmailLog::create([
            'email' => $email,
            'subject' => $subject,
            'email_type' => 'manual',
            'status' => $status,
            'error_message' => $error,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Get email logs for an email address
     *
     * @param string $email
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLogsForEmail($email, $limit = 50)
    {
        return EmailLog::where('email', $email)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get failed emails for retry
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFailedEmails($limit = 10)
    {
        return EmailLog::where('status', 'failed')
            ->where('created_at', '>', now()->subHours(24))
            ->oldest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get email statistics for a date range
     *
     * @param \Carbon\Carbon $from
     * @param \Carbon\Carbon $to
     * @return array Statistics
     */
    public function getStatistics($from = null, $to = null)
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();

        $query = EmailLog::whereBetween('created_at', [$from, $to]);

        return [
            'total' => $query->count(),
            'sent' => $query->where('status', 'sent')->count(),
            'failed' => $query->where('status', 'failed')->count(),
            'queued' => $query->where('status', 'queued')->count(),
            'by_type' => $query->groupBy('email_type')->selectRaw('email_type, COUNT(*) as count')->get(),
        ];
    }
}
