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
        Schema::table('skill_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('availability_id')->nullable();
        $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_requests', function (Blueprint $table) {
            $table->dropForeign(['availability_id']);
            $table->dropColumn('availability_id');
        });
    }
};
