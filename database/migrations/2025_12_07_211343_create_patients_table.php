<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Auto-generated file number (unique identifier for clinic records)
            $table->integer('file_number')->unique()->nullable();

            // Patient personal information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();

            // Demographics
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();

            // Payment type
            $table->enum('type', ['cash', 'insurance'])->default('cash');

            // Insurance information (if type = insurance)
            $table->foreignId('insurance_company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('insurance_card_number')->nullable();
            $table->string('insurance_policyholder')->nullable();
            $table->date('insurance_expiry_date')->nullable();

            // Additional information
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index('file_number');
            $table->index('phone');
            $table->index('insurance_company_id');
            $table->index('type');
            $table->index('is_active');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
