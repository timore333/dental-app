<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship (can belong to Patient, InsuranceCompany, etc.)
            $table->unsignedBigInteger('documentable_id');
            $table->string('documentable_type');

            // Document details
            $table->enum('type', ['insurance_card', 'service_request', 'approval', 'prescription', 'receipt', 'other']);
            $table->string('file_path'); // storage path
            $table->string('file_name'); // original filename
            $table->integer('file_size'); // in bytes
            $table->string('mime_type'); // file type

            // Audit fields
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for polymorphic queries
            $table->index(['documentable_id', 'documentable_type'],'idx_doc_morphable');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
