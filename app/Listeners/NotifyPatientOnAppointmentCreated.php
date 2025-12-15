<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Jobs\SendSmsNotification;
use App\Jobs\SendEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * NotifyPatientOnAppointmentCreated Listener
 * Sends SMS, email, and in-app notifications when appointment is created
 */
class NotifyPatientOnAppointmentCreated implements ShouldQueue
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
     * @param AppointmentCreated $event
     * @return void
     */
    public function handle(AppointmentCreated $event)
    {
        try {
            $appointment = $event->appointment;
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
                Log::info('Appointment notifications disabled', ['user_id' => $user->id]);
                return;
            }

            $appointmentTime = $appointment->appointment_date_time->format('h:i A');
            $appointmentDate = $appointment->appointment_date_time->format('l, F j, Y');

            $message = "Your appointment at Thnaya Clinic has been confirmed for {$appointmentDate} at {$appointmentTime}.";
            $subject = "Appointment Confirmed - {$appointmentDate}";

            // Send SMS
            if ($preferences->isSmsEnabled() && $user->phone) {
                dispatch(new SendSmsNotification(
                    $user->phone,
                    $message,
                    'appointment_confirmation',
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
                    'emails.appointment-confirmation',
                    [
                        'patient' => $patient,
                        'appointment' => $appointment,
                        'appointmentTime' => $appointmentTime,
                        'appointmentDate' => $appointmentDate,
                    ],
                    'appointment_confirmation',
                    auth()->id(),
                    'appointment',
                    $appointment->id
                ));
            }

            // Send In-App Notification
            if ($preferences->isInAppEnabled()) {
                $notificationService = app('App\Services\NotificationService');
                $notificationService->createNotification(
                    $user->id,
                    'Appointment Confirmed',
                    $message,
                    'appointment',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_date' => $appointmentDate,
                        'appointment_time' => $appointmentTime,
                    ]
                );
            }

            Log::info('Appointment creation notification sent', [
                'appointment_id' => $appointment->id,
                'user_id' => $user->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to notify patient on appointment creation', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure
     *
     * @param AppointmentCreated $event
     * @param \Throwable $exception
     * @return void
     */
    public function failed(AppointmentCreated $event, \Throwable $exception)
    {
        Log::error('NotifyPatientOnAppointmentCreated listener failed', [
            'error' => $exception->getMessage(),
        ]);
    }
}
