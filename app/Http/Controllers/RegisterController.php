<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Skill;
use App\Models\Category;
use App\Models\Availability;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Show the registration form
    public function create()
    {
        $skills = Skill::all();
        $availabilities = Availability::all();
        $preferences = Preference::all(); // Fetch preferences
    
        $timeSlots = [];
        $start = strtotime('08:00:00');
        $end = strtotime('13:00:00');
    
        for ($i = $start; $i <= $end; $i += 3600) {
            $timeSlots[] = date('H:i', $i);
        }
    
        return view('auth.register', compact('skills', 'availabilities', 'timeSlots', 'preferences'));
    }
    

    // Handle the registration request
   // Handle the registration request
   public function store(Request $request)
{
    $validated = $request->validate([
        // Validation rules
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
            function ($attribute, $value, $fail) {
                $allowedEmail = 'admin@apiit.lk';
                $domain = 'students.apiit.lk';
                if ($value !== $allowedEmail && !str_ends_with($value, "@$domain")) {
                    $fail("The $attribute must be an email address with the domain $domain.");
                }
            },
        ],
        'password' => ['required', 'confirmed', 'min:8'],
        'level' => ['required', 'string', 'in:L4,L5,L6'],
        'availabilities' => ['required', 'array'],
        'availabilities.*' => ['exists:availabilities,id'],
        'skills' => ['required', 'array'],
        'skills.*.id' => ['exists:skills,id'], // Validate skill IDs
        'skills.*.preference_id' => ['required', 'exists:preferences,id'], // Validate preference IDs
    ]);

    // Create the user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'level' => $validated['level'],
    ]);

    // Attach skills with preferences to the user
    foreach ($validated['skills'] as $skill) {
        $user->skills()->attach($skill['id'], ['preference_id' => $skill['preference_id']]);
    }

    // Assign the default role "peer-student" to the user
    $defaultRole = Role::where('RoleName', 'peer-student')->first();
    if ($defaultRole) {
        $user->roles()->attach($defaultRole->RoleID);
    }

    // Attach availabilities
    $user->availabilities()->attach($validated['availabilities']);

    // Redirect to the dashboard
    return redirect()->route('dashboard');
}

}   
