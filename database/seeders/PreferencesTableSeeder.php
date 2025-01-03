<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('preferences')->insert([
            ['name' => 'group-session', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'one-one session', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'both', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

