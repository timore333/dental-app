<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_imports', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('original_filename');
            $table->integer('total_rows')->default(0);
            $table->integer('success_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('errors')->nullable(); // Failed rows with details
            $table->json('summary')->nullable(); // created, updated, skipped counts
            $table->text('notes')->nullable();
            $table->foreignId('imported_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['imported_by', 'created_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_imports');
    }
};
