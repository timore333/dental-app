<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();

            // Email address
            $table->string('email', 255)->index();

            // Email subject
            $table->string('subject', 255);

            // Type of email (receipt, reminder, appointment, insurance, birthday, etc)
            $table->string('email_type')->index();

            // Status: queued, sent, failed, bounced
            $table->enum('status', ['queued', 'sent', 'failed', 'bounced'])->default('queued')->index();

            // Error message if failed
            $table->text('error_message')->nullable();

            // Email provider response
            $table->json('provider_response')->nullable();

            // Created by (user who triggered the email)
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();

            // Related patient/appointment/payment
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();

            // When email was actually sent
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['email', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index(['email_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
