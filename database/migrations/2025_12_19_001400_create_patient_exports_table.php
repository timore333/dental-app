<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patient_exports', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('format'); // excel, pdf, sql
            $table->string('template')->default('complete'); // complete, basic, financial
            $table->json('filters')->nullable(); // date range, category, etc
            $table->json('selected_fields')->nullable(); // which columns included
            $table->integer('record_count')->default(0);
            $table->string('file_path');
            $table->foreignId('exported_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamps();

            $table->index(['exported_by', 'created_at']);
            $table->index('format');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_exports');
    }
};
