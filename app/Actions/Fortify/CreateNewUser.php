<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users',// Custom validation to ensure the email domain is 'apiit.students.lk'
            // Custom domain validation, excluding admin email
        function ($attribute, $value, $fail) {
            $allowedEmail = 'admin@apiit.lk'; // admin's email adress domain isnt students.apiit.lk 
            $domain = 'students.apiit.lk';
            
            if ($value !== $allowedEmail && !str_ends_with($value, "@$domain")) {
                $fail("The $attribute must be an email address with the domain $domain.");
            }
        },
        ],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            // Create the user
            $user = tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) {
                $this->createTeam($user);
            });

            // Assign the default role of 'peer-student' to the user
            $peerStudentRole = Role::where('RoleName', 'peer-student')->first();
            if ($peerStudentRole) {
                $user->roles()->attach($peerStudentRole->RoleID); // Attach the role using the relationship
            }

            return $user;
        });
        
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
