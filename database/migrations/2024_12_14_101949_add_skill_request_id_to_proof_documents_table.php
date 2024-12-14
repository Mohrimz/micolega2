<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkillRequestIdToProofDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proof_documents', function (Blueprint $table) {
            // Add the skill_request_id column
            $table->foreignId('skill_request_id')
                ->nullable() // Allow null values if needed
                ->constrained('skill_requests') // Reference the skill_requests table
                ->onDelete('cascade'); // Handle deletion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proof_documents', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['skill_request_id']);
            $table->dropColumn('skill_request_id');
        });
    }
}
