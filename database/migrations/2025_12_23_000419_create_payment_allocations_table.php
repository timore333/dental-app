<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->unsignedBigInteger('advance_credit_id')->nullable();
            $table->decimal('allocated_amount', 10, 2);
            $table->timestamp('allocation_date');
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->cascadeOnDelete();
            $table->foreign('bill_id')->references('id')->on('bills')->nullableOnDelete();
            $table->foreign('advance_credit_id')->references('id')->on('advance_credits')->nullableOnDelete();
            $table->index(['payment_id', 'bill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
