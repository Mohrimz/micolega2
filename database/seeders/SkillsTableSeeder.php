<?php

namespace Database\Seeders;
use App\Models\Skill;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Dummy skills data for different categories
       $skills = [
        [
            'name' => 'CTF Challenge Solver',
            'description' => 'Learn how to solve specific CTF challenges on HackTheBox and earn flags.',
            'category_id' => 1, 
        ],
        
        [
            'name' => 'Business Strategy Mastery',
            'description' => 'Master business strategy through  Capsim  to earn certificates or badges.',
            'category_id' => 4, 
        ],
    ];

    foreach ($skills as $skill) {
        Skill::create($skill);
    }
    }
}
