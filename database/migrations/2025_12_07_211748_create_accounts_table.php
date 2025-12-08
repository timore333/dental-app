<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship (can belong to Patient or InsuranceCompany)
            $table->unsignedBigInteger('accountable_id');
            $table->string('accountable_type'); // Patient or InsuranceCompany

            // Account balance
            $table->decimal('balance', 10, 2)->default(0);

            // Timestamps
            $table->timestamps();

            // Composite unique index - one account per entity
            $table->unique(['accountable_id', 'accountable_type'],'acc_accountable_unique');

            // Index for lookups
            $table->index(['accountable_id', 'accountable_type'],'idx_acc_morphable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
