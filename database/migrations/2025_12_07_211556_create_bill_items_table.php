<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedure_id')->constrained()->cascadeOnDelete();

            // Line item details
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2); // quantity * unit_price

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('bill_id');
            $table->index('procedure_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
