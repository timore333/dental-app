<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_ledger_entries', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();

            // Transaction details
            $table->enum('transaction_type', ['debit', 'credit']);
            $table->decimal('amount', 10, 2);

            // Reference information
            $table->string('reference_type'); // bill, payment, adjustment, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description');

            // Audit fields
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Timestamps (created_at only, no updated_at for ledger immutability)
            $table->timestamp('created_at')->useCurrent();

            // Indexes for common queries
            $table->index('account_id');
            $table->index('created_at');
            $table->index(['reference_type', 'reference_id'],'idx_ledger_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_ledger_entries');
    }
};
