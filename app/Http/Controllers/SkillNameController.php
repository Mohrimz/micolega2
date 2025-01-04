<?php

namespace App\Http\Controllers;

use App\Models\RequestedSkill;
use Illuminate\Http\Request;
use App\Models\Skill; 

class SkillNameController extends Controller
{
    // Show pending skills
    public function index()
    {
        // Fetch pending requested skills
        $skills = RequestedSkill::where('status', 'pending')->get(['id', 'name', 'status']);

        return view('lol', compact('skills'));
    }

    // Accept a skill
    public function acceptSkill(Request $request, $id)
{
    $request->validate([
        'description' => 'required|string|max:255',
    ]);

    $requestedSkill = RequestedSkill::findOrFail($id);

    // Add the skill to the `skills` table
    Skill::create([
        'name' => $requestedSkill->name,
        'description' => $request->description,
        'category_id' => $requestedSkill->category_id,
    ]);

    // Update the status in the `requested_skills` table
    $requestedSkill->status = 'approved';
    $requestedSkill->save();

    return redirect()->back()->with('success', 'Skill approved and added successfully.');
}


public function rejectSkill($id)
{
    $requestedSkill = RequestedSkill::findOrFail($id);
    $requestedSkill->status = 'rejected';
    $requestedSkill->save();

    return redirect()->back()->with('success', 'Skill rejected successfully.');
}

    
}
