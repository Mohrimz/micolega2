<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('group_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained()->onDelete('cascade'); // Foreign key constraint
            $table->string('level');
            $table->date('date');
            $table->time('time');
            $table->string('reject_reason')->nullable(); // Adding reject_reason column
            $table->unsignedBigInteger('created_by')->nullable(); // Creator reference
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->string('status')->default('active'); // Adding status column
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('group_courses');
    }
};
