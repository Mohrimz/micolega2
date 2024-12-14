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
            // Add tutor_id if it doesn't exist
            if (!Schema::hasColumn('skill_requests', 'tutor_id')) {
                $table->string('tutor_id')->after('user_id');
            }

            // Add skill_id if it doesn't exist
            if (!Schema::hasColumn('skill_requests', 'skill_id')) {
                $table->string('skill_id')->after('tutor_id');
            }

            // Update the status column to have a default value of 'pending'
            if (Schema::hasColumn('skill_requests', 'status')) {
                $table->string('status')->default('pending')->change();
            }

            // Drop columns if they exist
            if (Schema::hasColumn('skill_requests', 'date')) {
                $table->dropColumn('date');
            }

            if (Schema::hasColumn('skill_requests', 'time')) {
                $table->dropColumn('time');
            }

            if (Schema::hasColumn('skill_requests', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_requests', function (Blueprint $table) {
            // Drop tutor_id and skill_id if they exist
            if (Schema::hasColumn('skill_requests', 'tutor_id')) {
                $table->dropColumn('tutor_id');
            }

            if (Schema::hasColumn('skill_requests', 'skill_id')) {
                $table->dropColumn('skill_id');
            }

            // Reset the default for the status column
            if (Schema::hasColumn('skill_requests', 'status')) {
                $table->string('status')->default(null)->change();
            }

            // Re-add columns if needed
            if (!Schema::hasColumn('skill_requests', 'date')) {
                $table->timestamp('date')->nullable();
            }

            if (!Schema::hasColumn('skill_requests', 'time')) {
                $table->timestamp('time')->nullable();
            }

            if (!Schema::hasColumn('skill_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }
};
