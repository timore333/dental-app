<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * SendAppointmentReminders Job
 * Scheduled job to send appointment reminders
 * Runs daily at 10 AM and sends reminders for appointments in the next 24 hours
 */
class SendAppointmentReminders implements ShouldQueue
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
            Log::info('SendAppointmentReminders job started');

            // Get appointments for next 24 hours
            $appointments = $this->getUpcomingAppointments();

            if ($appointments->isEmpty()) {
                Log::info('No upcoming appointments for reminders');
                return;
            }

            Log::info("Found {$appointments->count()} upcoming appointments");

            $smsService = app('App\Services\SmsService');
            $emailService = app('App\Services\EmailService');
            $notificationService = app('App\Services\NotificationService');

            foreach ($appointments as $appointment) {
                try {
                    $this->sendAppointmentReminder(
                        $appointment,
                        $smsService,
                        $emailService,
                        $notificationService
                    );
                } catch (Exception $e) {
                    Log::error('Failed to send appointment reminder', [
                        'appointment_id' => $appointment->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('SendAppointmentReminders job completed');

        } catch (Exception $e) {
            Log::error('SendAppointmentReminders job error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get appointments scheduled for next 24 hours
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getUpcomingAppointments()
    {
        $now = now();
        $tomorrow = $now->copy()->addDay();

        return Appointment::whereBetween('appointment_date_time', [$now, $tomorrow])
            ->where('status', '!=', 'cancelled')
            ->where('reminder_sent', false)
            ->with('patient', 'patient.user')
            ->get();
    }

    /**
     * Send appointment reminder
     *
     * @param Appointment $appointment
     * @param mixed $smsService
     * @param mixed $emailService
     * @param mixed $notificationService
     * @return void
     */
    protected function sendAppointmentReminder(
        $appointment,
        $smsService,
        $emailService,
        $notificationService
    ) {
        $patient = $appointment->patient;
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

        if (!$preferences->appointmentRemindersEnabled()) {
            Log::info('Appointment reminders disabled for user', ['user_id' => $user->id]);
            return;
        }

        $appointmentTime = $appointment->appointment_date_time->format('h:i A');
        $appointmentDate = $appointment->appointment_date_time->format('l, F j, Y');

        $message = "Reminder: You have an appointment at Thnaya Clinic on {$appointmentDate} at {$appointmentTime}. See you soon!";
        $subject = "Appointment Reminder - {$appointmentDate}";

        // Send SMS
        if ($preferences->isSmsEnabled() && !$preferences->isQuietHour() && $user->phone) {
            dispatch(new SendSmsNotification(
                $user->phone,
                $message,
                'appointment_reminder',
                auth()->id(),
                'appointment',
                $appointment->id
            ));
        }

        // Send Email
        if ($preferences->isEmailEnabled()) {
            dispatch(new SendEmailNotification(
                $user->email,
                $subject,
                'emails.appointment-reminder',
                [
                    'patient' => $patient,
                    'appointment' => $appointment,
                    'appointmentTime' => $appointmentTime,
                    'appointmentDate' => $appointmentDate,
                    'message' => $message,
                ],
                'appointment_reminder',
                auth()->id(),
                'appointment',
                $appointment->id
            ));
        }

        // Send In-App Notification
        if ($preferences->isInAppEnabled()) {
            $notificationService->createNotification(
                $user->id,
                'Appointment Reminder',
                $message,
                'appointment',
                [
                    'appointment_id' => $appointment->id,
                    'appointment_date' => $appointmentDate,
                    'appointment_time' => $appointmentTime,
                ]
            );
        }

        // Mark reminder as sent
        $appointment->update(['reminder_sent' => true]);

        Log::info('Appointment reminder sent', [
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'user_id' => $user->id,
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
        Log::critical('SendAppointmentReminders job failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
