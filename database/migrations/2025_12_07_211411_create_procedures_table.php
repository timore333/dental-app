<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();

            // Procedure identification
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('default_price', 10, 2);

            // Classification
            $table->string('category'); // consultation, filling, extraction, crown, cleaning, etc.

            // Status
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('code');
            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
