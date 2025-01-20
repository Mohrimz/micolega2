<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\RequestedSkill;
use App\Models\UserRequestSkill;
use Illuminate\Http\Request;

class SkillNameController extends Controller
{
    public function showPendingSkills()
    {
        $skills = RequestedSkill::where('status', 'pending')->get();
        return view('lol', compact('skills'));
    }

    public function acceptSkill(Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:requested_skills,id',
            'description' => 'required|string|max:255',
        ]);

        // Find the requested skill
        $requestedSkill = RequestedSkill::findOrFail($request->skill_id);

        // Move the skill to the `skills` table
        $skill = Skill::create([
            'name' => $requestedSkill->name,
            'description' => $request->description,
            'category_id' => $requestedSkill->category_id,
        ]);

        // Update user_request_skills with new skill_id
        $userRequestSkills = UserRequestSkill::where('requested_skill_id', $requestedSkill->id)->get();
        foreach ($userRequestSkills as $userRequestSkill) {
            $userRequestSkill->delete();
            \DB::table('skill_user')->insert([
                'user_id' => $userRequestSkill->user_id,
                'skill_id' => $skill->id,
                'preference_id' => $userRequestSkill->preference_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mark the requested skill as approved
        $requestedSkill->status = 'approved';
        $requestedSkill->save();

        return redirect()->back()->with('success', 'Skill accepted and added successfully.');
    }
    public function rejectSkill(Request $request)
{
    $request->validate([
        'skill_id' => 'required|exists:requested_skills,id',
    ]);

    $requestedSkill = RequestedSkill::findOrFail($request->skill_id);
    $requestedSkill->status = 'rejected';
    $requestedSkill->save();

    return redirect()->back()->with('success', 'Skill rejected successfully.');
}

}
