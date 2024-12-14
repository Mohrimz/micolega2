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
        Schema::table('proof_documents', function (Blueprint $table) {
            // Add new fields
            $table->string('notes')->nullable()->after('status'); // Optional notes field
            $table->string('rejection_reason')->nullable()->after('notes'); // Optional rejection reason field
            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proof_documents', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn('notes');
            $table->dropColumn('rejection_reason');
        });
    }
};
