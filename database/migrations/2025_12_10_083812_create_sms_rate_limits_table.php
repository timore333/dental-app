<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_rate_limits', function (Blueprint $table) {
            $table->id();

            // Phone number to limit
            $table->string('phone', 20)->unique()->index();

            // Count of SMS sent to this phone today
            $table->integer('count_today')->default(0);

            // Count of SMS sent this hour
            $table->integer('count_this_hour')->default(0);

            // Last SMS sent time
            $table->timestamp('last_sms_at')->nullable();

            // Reset time (when count resets)
            $table->timestamp('reset_at')->nullable();

            // Is this phone rate-limited?
            $table->boolean('is_limited')->default(false);

            // Reason for limitation
            $table->string('reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_rate_limits');
    }
};
