<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();

            // Phone number in Egyptian format (201XXXXXXXXX)
            $table->string('phone', 20)->index();

            // SMS message content
            $table->text('message');

            // Type of SMS (appointment, payment, welcome, reminder, etc)
            $table->string('message_type')->index();

            // Message ID from E-Push API response
            $table->string('vodafone_message_id', 100)->nullable()->unique();

            // Status: sent, failed, pending, delivered
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending')->index();

            // Full API response from E-Push
            $table->json('response')->nullable();

            // Error message if failed
            $table->text('error_message')->nullable();

            // Amount charged by E-Push
            $table->decimal('transaction_price', 8, 4)->nullable();

            // Remaining SMS balance after sending
            $table->decimal('net_balance', 10, 2)->nullable();

            // Created by (user who triggered the SMS)
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();

            // Related patient/appointment/payment
            $table->string('related_type')->nullable(); // Model type
            $table->unsignedBigInteger('related_id')->nullable(); // Model ID

            $table->timestamps();

            // Indexes for queries
            $table->index(['phone', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['message_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
