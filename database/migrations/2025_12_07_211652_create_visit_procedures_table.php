<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_procedures', function (Blueprint $table) {
            $table->id();

            // Relationships (pivot table for visit-procedure many-to-many)
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedure_id')->constrained()->cascadeOnDelete();

            // Price at time of procedure (may differ from current default price)
            $table->decimal('price_at_time', 10, 2);
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('visit_id');
            $table->index('procedure_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_procedures');
    }
};
