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
            $table->foreignId('skill_request_id')->constrained()->onDelete('cascade')->after('skill_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proof_documents', function (Blueprint $table) {
            $table->dropForeign(['skill_request_id']);
            $table->dropColumn('skill_request_id');

        });
    }
};
