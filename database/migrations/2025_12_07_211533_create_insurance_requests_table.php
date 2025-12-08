<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_requests', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('insurance_company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();

            // Request tracking
            $table->dateTime('request_date');
            $table->enum('status', ['submitted', 'approved', 'rejected', 'partial'])->default('submitted');

            // Document reference
            $table->unsignedBigInteger('request_document_id')->nullable();
            $table->string('request_document_type')->default('Document');

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('patient_id');
            $table->index('insurance_company_id');
            $table->index('doctor_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_requests');
    }
};
