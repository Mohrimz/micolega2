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
            $table->string('notes')->nullable()->after('status'); // Adding notes column
            $table->string('rejection_reason')->nullable()->after('notes'); // Adding rejection reason column
            $table->foreignId('skill_request_id')->nullable()
                  ->constrained()->onDelete('set null')->after('rejection_reason'); // Adding skill_request_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proof_documents', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('rejection_reason');
            $table->dropForeign(['skill_request_id']);
            $table->dropColumn('skill_request_id');
        });
    }
};
