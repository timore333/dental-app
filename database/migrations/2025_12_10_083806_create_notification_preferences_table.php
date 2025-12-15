<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();

            // User who owns these preferences
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            // Notification channels enabled
            $table->boolean('sms_enabled')->default(true);
            $table->boolean('email_enabled')->default(true);
            $table->boolean('in_app_enabled')->default(true);

            // Notification types
            $table->boolean('appointment_reminders')->default(true);
            $table->boolean('payment_notifications')->default(true);
            $table->boolean('insurance_notifications')->default(true);
            $table->boolean('promotional_notifications')->default(false);
            $table->boolean('marketing_sms')->default(false);

            // Time preferences
            $table->time('quiet_hours_start')->nullable(); // Don't send SMS during these hours
            $table->time('quiet_hours_end')->nullable();

            // Frequency preferences
            $table->enum('email_frequency', ['immediately', 'daily', 'weekly', 'never'])->default('immediately');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
