<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_approvals', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('insurance_request_id')->constrained()->cascadeOnDelete();

            // Document reference
            $table->unsignedBigInteger('approval_document_id')->nullable();
            $table->string('approval_document_type')->default('Document');

            // Approval details
            $table->dateTime('approval_date');

            // FIX: Remove default from JSON columns
            // JSON columns cannot have default values in MySQL 5.7
            $table->json('approved_procedures')->nullable();
            $table->json('rejected_procedures')->nullable();

            $table->decimal('approved_amount', 10, 2)->nullable();
            $table->text('approval_notes')->nullable();

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('insurance_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_approvals');
    }
};
