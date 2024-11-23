<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Team;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@apiit.lk',
            'password' => Hash::make('Admin123'), // Use a secure password in production
        ]);

        // Create a personal team for the admin user
        $adminUser->ownedTeams()->save(Team::forceCreate([
            'user_id' => $adminUser->id,
            'name' => $adminUser->name . "'s Team",
            'personal_team' => true,
        ]));

        // Assign the 'admin' role to the user
        $adminRole = Role::where('RoleName', 'admin')->first();
        if ($adminRole) {
            $adminUser->roles()->attach($adminRole->RoleID);
        }
    }
}
