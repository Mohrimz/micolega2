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
        Schema::table('skill_user', function (Blueprint $table) {
            $table->foreignId('preference_id')->nullable()->constrained('preferences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_user', function (Blueprint $table) {
            $table->dropForeign(['preference_id']);
            $table->dropColumn('preference_id');
        });
    }
};
