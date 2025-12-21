<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();

            // Visit details
            $table->dateTime('visit_date');
            $table->text('chief_complaint');
            $table->text('diagnosis')->nullable();
            $table->text('treatment_notes')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

               // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Indexes
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('visit_date');
            $table->index('appointment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
