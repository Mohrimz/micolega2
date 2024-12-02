<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'level' => fake()->randomElement(['L4', 'L5', 'L6']),  // Add level field if required
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }

    /**
     * Assign a specific role to the user.
     *
     * @param string $roleName
     * @return static
     */
    public function assignRole(string $roleName): static
    {
        return $this->afterCreating(function (User $user) use ($roleName) {
            $role = Role::where('RoleName', $roleName)->first(); // Ensure Role exists
            if ($role) {
                $user->roles()->attach($role->RoleID);
            }
        });
    }

    /**
     * Assign the default peer-student role to the user.
     *
     * @return static
     */
    public function asPeerStudent(): static
    {
        return $this->assignRole('peer-student');
    }

    /**
     * Assign the peer-tutor role to the user.
     *
     * @return static
     */
    public function asPeerTutor(): static
    {
        return $this->assignRole('peer-tutor');
    }

    /**
     * Assign the admin role to the user.
     *
     * @return static
     */
    public function asAdmin(): static
    {
        return $this->assignRole('admin');
    }
}
