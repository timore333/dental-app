<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->string('reference_number')->nullable(); // cheque number, transaction ID, etc.

            // Receipt tracking
            $table->string('receipt_number')->unique();
            $table->text('notes')->nullable();
             $table->foreignId('doctor_id')->nullable()->constrained('users')->cascadeOnDelete();

            $table->index('doctor_id');

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('bill_id');
            $table->index('payment_date');
            $table->index('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
