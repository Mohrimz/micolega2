<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proof_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to the users table
            $table->foreignId('skill_id')->constrained()->onDelete('cascade'); // Links to the skills table
            $table->string('document_path'); // Path to the stored document
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status of the document
            $table->text('notes')->nullable(); // Notes for additional information$table->foreignId('skill_request_id')->nullable()->constrained()->onDelete('cascade'); // Links to skill requests table
            $table->text('rejection_reason')->nullable(); // Reason for rejection
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_documents');
    }
};
