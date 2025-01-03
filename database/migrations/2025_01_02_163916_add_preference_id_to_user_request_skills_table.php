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
        Schema::table('user_request_skills', function (Blueprint $table) {
            $table->foreignId('preference_id')
                ->constrained('preferences')
                ->onDelete('cascade'); // Use 'restrict' if you don't want to allow deletion of the parent row
        });
    }
    
    public function down()
    {
        Schema::table('user_request_skills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('preference_id');
        });
    }
    
};
