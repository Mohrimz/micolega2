<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Skill;
use App\Models\Category;
use App\Models\Availability;
use App\Models\Preference;
use App\Models\RequestedSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Show the registration form
    public function create()
    {
        $skills = Skill::all(); // Fetch all skills
        $requested_skills = RequestedSkill::where('status', 'pending')->get(); // Fetch requested skills with pending status
        $availabilities = Availability::all(); // Fetch all availabilities
        $preferences = Preference::all(); // Fetch preferences
        $categories = Category::all(); 
    
        // Generate time slots
        $timeSlots = [];
        $start = strtotime('08:00:00');
        $end = strtotime('13:00:00');
    
        for ($i = $start; $i <= $end; $i += 3600) {
            $timeSlots[] = date('H:i', $i);
        }
    
        // Pass all data to the view
        return view('auth.register', compact('skills', 'requested_skills', 'availabilities', 'timeSlots', 'preferences','categories'));
    }
    
    

    // Handle the registration request
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
        'skills' => ['nullable', 'array'],
        'skills.*.id' => ['exists:skills,id'], // Validate skill IDs
        'skills.*.preference_id' => ['required_with:skills.*.id', 'exists:preferences,id'],
        'requested_skills' => ['nullable', 'array'],
        'requested_skills.*.id' => ['nullable', 'exists:requested_skills,id'],
        'requested_skills.*.preference_id' => ['required_with:requested_skills.*.id', 'exists:preferences,id'],
        'new_requested_skill' => ['nullable', 'string', 'max:255'],
        'new_requested_skill_preference_id' => ['nullable', 'exists:preferences,id'],
        'new_requested_skill_category_id' => ['nullable', 'exists:categories,id'],

        
    ]);

    // Create the user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'level' => $validated['level'],
    ]);

    // Attach skills with preferences
    if (isset($validated['skills'])) {
        foreach ($validated['skills'] as $skill) {
            $user->skills()->attach($skill['id'], ['preference_id' => $skill['preference_id']]);
        }
    }

    // Attach requested_skills with preferences
    if (isset($validated['requested_skills'])) {
        foreach ($validated['requested_skills'] as $requestedSkill) {
            $user->requestedSkills()->attach($requestedSkill['id'], ['preference_id' => $requestedSkill['preference_id']]);
        }
    }

    // Add a new requested skill if provided
    if (!empty($validated['new_requested_skill'])) {
        $newRequestedSkill = RequestedSkill::create([
            'name' => $validated['new_requested_skill'],
            'status' => 'pending',
            'category_id' => $validated['new_requested_skill_category_id'], // Save the category
        ]);
    
        // Attach the new skill to the user with the preference ID
        $user->requestedSkills()->attach($newRequestedSkill->id, [
            'preference_id' => $validated['new_requested_skill_preference_id'],
        ]);
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
