<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();

            // Bill identification (auto-generated format: BILL-YYYY-0001)
            $table->string('bill_number')->unique();

            // Relationships
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('insurance_company_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('insurance_request_id')->nullable()->constrained('insurance_requests')->nullOnDelete();

            // Bill details
            $table->dateTime('bill_date');
            $table->enum('type', ['cash', 'insurance'])->default('cash');

            // Amounts
            $table->decimal('c', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);

            // Status tracking
            $table->enum('status', ['draft', 'issued', 'partially_paid', 'fully_paid', 'cancelled'])->default('draft');
            $table->date('due_date')->nullable();

            // Additional information
            $table->text('notes')->nullable();

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('visit_id')->nullable()->constrained('visits')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for common queries
            $table->index('bill_number');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('insurance_company_id');
            $table->index('status');
            $table->index('bill_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
