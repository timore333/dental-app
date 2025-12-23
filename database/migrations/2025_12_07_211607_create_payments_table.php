<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('bill_id')->constrained()->cascadeOnDelete();

            // Payment details
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'cheque', 'card', 'bank_transfer']);
            $table->dateTime('payment_date');

            // Receipt tracking
            $table->string('receipt_number')->unique();
            $table->text('notes')->nullable();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('completed')->comment('completed, pending, cancelled');
            $table->string('payment_source_type')->nullable()->comment('advance_payment, overpayment, etc');
            $table->unsignedBigInteger('payment_source_id')->nullable();
            $table->string('reference_number')->unique('payments_reference_number_unique')->nullable();

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();


            // Indexes
            $table->index('doctor_id');
            $table->index('bill_id');
            $table->index('payment_date');
            $table->index('receipt_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
