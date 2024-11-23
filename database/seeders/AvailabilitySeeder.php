<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $times = [];

        // Generate times from 08:00 to 21:00 (9 PM)
        for ($hour = 8; $hour <= 21; $hour++) {
            $formattedTime = sprintf('%02d:00:00', $hour);
            $times[] = $formattedTime;
        }

        // Insert availability data for each day and each time
        foreach ($days as $day) {
            foreach ($times as $time) {
                DB::table('availabilities')->insert([
                    'date' => $day,
                    'time' => $time,
                ]);
            }
        }
    }
}
