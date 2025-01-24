<?php
namespace App\Http\Controllers;

use App\Models\RequestedSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillRequestController extends Controller
{
    public function index()
    {
        $requestedSkills = RequestedSkill::where('status', 'pending')->with('user')->get();
        return view('admin.requestedSkills', compact('requestedSkills'));
    }

    public function uploadProof(Request $request)
    {
        $request->validate([
            'proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'skill_id' => 'required|exists:requested_skills,id',
        ]);

        $skill = RequestedSkill::findOrFail($request->skill_id);
        $proofPath = $request->file('proof')->store('proof_documents', 'public');

        $skill->update([
            'proof' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Proof uploaded successfully!');
    }

    public function approveSkill($id)
    {
        $skill = RequestedSkill::findOrFail($id);
        $skill->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Skill approved successfully!');
    }

    public function rejectSkill($id)
    {
        $skill = RequestedSkill::findOrFail($id);
        $skill->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Skill rejected successfully!');
    }
    public function tutorSkills()
{
    // Fetch the skills requested by the current tutor
    $requestedSkills = RequestedSkill::where('status', 'pending')->get();

    return view('tutor.skills', compact('requestedSkills'));
}

public function adminSkills()
{
    // Fetch all pending requested skills for admin review
    $requestedSkills = RequestedSkill::where('status', 'pending')->with('user')->get();

    return view('admin.requestedSkills', compact('requestedSkills'));
}

}
