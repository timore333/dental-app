<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();

            // Appointment details
            $table->dateTime('appointment_date')->nullable();// will be deleted
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no-show'])->default('scheduled');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('appointment_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
