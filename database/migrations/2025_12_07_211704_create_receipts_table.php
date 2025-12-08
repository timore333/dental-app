<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('payment_id')->unique()->constrained()->cascadeOnDelete();

            // Receipt details
            $table->string('receipt_number')->unique();
            $table->dateTime('receipt_date');

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('receipt_number');
            $table->index('receipt_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
