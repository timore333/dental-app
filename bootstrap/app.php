<?php

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckSessionTimeout;
use App\Http\Middleware\LogActivity;
use App\Http\Middleware\RoleBasedRedirect;
use App\Http\Middleware\SetLocale;
use App\Jobs\SendAppointmentReminders;
use App\Jobs\SendBirthdayGreeting;
use App\Jobs\SendHolidayGreeting;
use App\Jobs\SendOverduePaymentNotifications;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Notifications\DatabaseNotification;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware aliases
        $middleware->alias([
            'session.timeout' => CheckSessionTimeout::class,
            'log.activity' => LogActivity::class,
            'role' => CheckRole::class,
            'permission' => CheckPermission::class,
            'role.redirect' => RoleBasedRedirect::class,
        ]);

        // Append custom middleware to web stack
        $middleware->web(append: [
            SetLocale::class,
            LogActivity::class,
            CheckSessionTimeout::class,
        ]);
    })
    ->withSchedule(function ($schedule): void {
        /**
         * ===================================
         * NOTIFICATION SYSTEM SCHEDULE
         * ===================================
         * All notification jobs scheduled here
         *
         * NOTE: Only jobs can use runInBackground()
         * Closures (callbacks) must NOT use runInBackground()
         */

        // Appointment Reminders - Daily at 10 AM
        // Sends reminders for appointments scheduled in the next 24 hours
        $schedule->job(new SendAppointmentReminders())
            ->dailyAt('10:00')
            ->name('send-appointment-reminders')
            ->withoutOverlapping()
            ->onOneServer();

        // Birthday Greetings - Daily at 8 AM
        // Sends birthday greetings and special offers to patients with birthdays today
        $schedule->job(new SendBirthdayGreeting())
            ->dailyAt('08:00')
            ->name('send-birthday-greeting')
            ->withoutOverlapping()
            ->onOneServer();

        // Holiday Greetings - Daily at 9 AM
        // Sends holiday greetings during holiday seasons
        $schedule->job(new SendHolidayGreeting())
            ->dailyAt('09:00')
            ->name('send-holiday-greeting')
            ->withoutOverlapping()
            ->onOneServer();

        // Overdue Payment Notifications - Daily at 2 PM
        // Sends reminders for overdue bill payments
        $schedule->job(new SendOverduePaymentNotifications())
            ->dailyAt('14:00')
            ->name('send-overdue-payment-notifications')
            ->withoutOverlapping()
            ->onOneServer();

        // Cleanup Old Logs - Daily at 11 PM
        // Removes logs older than 14 days
        // ✅ Using command (not closure) so runInBackground() is OK
        $schedule->command('logs:clear')
            ->dailyAt('23:00')
            ->name('cleanup-logs')
            ->onOneServer();

        // Cleanup Old Notifications - Daily at 12 AM
        // Removes database notifications older than 90 days
        $schedule->call(function (): void {
            DatabaseNotification::where(
                'created_at',
                '<',
                now()->subDays(90)
            )->delete();
        })
            ->dailyAt('00:00')
            ->name('cleanup-old-notifications')
            ->onOneServer();

        // Cleanup Old SMS Logs - Weekly on Monday at 1 AM
        // Removes SMS logs older than 6 months for database optimization
        $schedule->call(function (): void {
            \App\Models\SmsLog::where(
                'created_at',
                '<',
                now()->subMonths(6)
            )->delete();
        })
            ->weeklyOn(1, '01:00')
            ->name('cleanup-old-sms-logs')
            ->onOneServer();

        // Cleanup Old Email Logs - Weekly on Monday at 1:30 AM
        // Removes email logs older than 6 months for database optimization

        $schedule->call(function (): void {
            \App\Models\EmailLog::where(
                'created_at',
                '<',
                now()->subMonths(6)
            )->delete();
        })
            ->weeklyOn(1, '01:30')
            ->name('cleanup-old-email-logs')
            ->onOneServer();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
