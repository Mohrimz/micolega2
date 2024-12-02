<?php



namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'RoleName' => $this->faker->word,  // Adjust this based on your role's fields
            // You can add other fields here based on the Role model's structure
        ];
    }
}
