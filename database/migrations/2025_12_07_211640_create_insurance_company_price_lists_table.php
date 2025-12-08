<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_company_price_lists', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('insurance_company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedure_id')->constrained()->cascadeOnDelete();

            // Price for this insurance company
            $table->decimal('price', 10, 2);

            // Timestamps
            $table->timestamps();

            // Composite unique index - one price per company-procedure combination
           $table->unique(['insurance_company_id', 'procedure_id'], 'ico_proc_unique');

            // Indexes for common queries
            $table->index('insurance_company_id');
            $table->index('procedure_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_company_price_lists');
    }
};
