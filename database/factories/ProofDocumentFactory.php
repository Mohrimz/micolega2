<?php

namespace Database\Factories;

use App\Models\ProofDocument;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProofDocument>
 */
class ProofDocumentFactory extends Factory
{
    protected $model = ProofDocument::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Creates a related user
            'skill_id' => Skill::factory(), // Creates a related skill
            'document_path' => $this->faker->filePath(), // Simulates a document path
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']), // Random status
            'notes' => $this->faker->sentence(), // Random notes
            'rejection_reason' => null, // Default to null unless rejected
        ];
    }
}
