<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Skill;
use App\Models\Category;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Show the registration form
    public function create()
    {
        // Fetch all skills and availabilities
        $skills = Skill::all();
        $availabilities = Availability::all();

        // Define the time slots from 08:00 to 21:00 at one-hour intervals
        $timeSlots = [];
        $start = strtotime('08:00:00');
        $end = strtotime('13:00:00');

        for ($i = $start; $i <= $end; $i += 3600) { // 3600 seconds = 1 hour
            $timeSlots[] = date('H:i', $i); // Format without seconds
        }

        // Pass data to the view
        return view('auth.register', compact('skills', 'availabilities', 'timeSlots'));
    }

    // Handle the registration request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    $allowedEmail = 'admin@apiit.lk'; // Admin email address
                    $domain = 'students.apiit.lk';
                    
                    if ($value !== $allowedEmail && !str_ends_with($value, "@$domain")) {
                        $fail("The $attribute must be an email address with the domain $domain.");
                    }
                },
            ],
            'password' => ['required', 'confirmed', 'min:8'],
            'level' => ['required', 'string', 'in:L4,L5,L6'], // Validate the "level" field
            'availabilities' => ['required', 'array'], // Ensure availabilities are selected
            'availabilities.*' => ['exists:availabilities,id'], // Validate each ID exists
            'skills' => ['required', 'array'], // Ensure that skills are selected
            'skills.*' => ['exists:skills,id'], // Validate that each selected skill ID exists
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'level' => $validated['level'], // Save the "level" field
        ]);

        // Attach the selected skills to the user (pivot table)
        $user->skills()->attach($validated['skills']);

        // Assign the default role "peer-student" to the user
        $defaultRole = Role::where('RoleName', 'peer-student')->first();
        if ($defaultRole) {
            $user->roles()->attach($defaultRole->RoleID); // Use the appropriate RoleID
        }

        // Attach each availability ID to the user
        $user->availabilities()->attach($validated['availabilities']);

        // Redirect to the intended page after registration
        return redirect()->route('dashboard');
    }
}
