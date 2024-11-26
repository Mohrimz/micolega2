<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillRequest;
use App\Models\ProofDocument;
use App\Models\SessionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeachController extends Controller
{
    /**
     * Display the "Teach" page with skills, session requests, approved, and rejected skills.
     */
    public function index()
    {
        // Fetch all skills with the number of users and their levels
        $skills = Skill::with(['users'])->get();

        // Calculate demand by level dynamically for each skill
        $skills->each(function ($skill) {
            $skill->levelDemand = [
                'L4' => $skill->users->where('level', 'L4')->count(),
                'L5' => $skill->users->where('level', 'L5')->count(),
                'L6' => $skill->users->where('level', 'L6')->count(),
            ];
        });

        // Fetch session requests for the logged-in tutor
        $sessionRequests = SessionRequest::where('tutor_id', Auth::id())->get();

        // Fetch approved skills for the logged-in user
        $approvedSkills = ProofDocument::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with('skill') // Eager load the associated skill
            ->get()
            ->pluck('skill'); // Extract only the skills

        // Fetch rejected skills for the logged-in user
        $rejectedSkills = ProofDocument::where('user_id', Auth::id())
            ->where('status', 'rejected')
            ->with(['skill']) // Eager load the associated skill
            ->get();

        return view('teach', compact('skills', 'sessionRequests', 'approvedSkills', 'rejectedSkills'));
    }

    /**
     * Handle the submission of proof documents for a skill request.
     */
    public function submitSkillRequest(Request $request)
    {
        // Validate the request data
        $request->validate([
            'proof.*' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
            'proof' => 'required|array|min:1',
        ]);

        // Create a new SkillRequest
        $skillRequest = SkillRequest::create([
            'user_id' => Auth::id(),
        ]);

        // Save proof documents
        if ($request->hasFile('proof')) {
            foreach ($request->file('proof') as $index => $file) {
                $path = $file->store('proof_documents');

                ProofDocument::create([
                    'user_id' => Auth::id(),
                    'skill_id' => $request->skill_id[$index],
                    'document_path' => $path,
                    'skill_request_id' => $skillRequest->id,
                ]);
            }
        }

        return redirect()->route('teach')->with('success', 'Proof documents submitted successfully!');
    }

    /**
     * Update the status of a proof document (approve or reject).
     */
    public function updateProofStatus(Request $request, $id)
    {
        $proofDocument = ProofDocument::findOrFail($id);

        // Validate the status input
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason_for_rejection' => 'required_if:status,rejected|max:255', // Validation for rejection reason
        ]);

        // Update the status and reason
        $proofDocument->status = $request->status;

        if ($request->status === 'rejected') {
            $proofDocument->reason_for_rejection = $request->reason_for_rejection;
        }

        $proofDocument->save();

        return redirect()->route('teach')->with('success', 'Proof document status updated successfully!');
    }

    /**
     * Fetch students registered for a specific skill.
     */
    public function getStudentsBySkill($skillId)
    {
        // Fetch students who registered for the specific skill
        $students = User::whereHas('skills', function ($query) use ($skillId) {
            $query->where('skills.id', $skillId);
        })->get(['id', 'name', 'level']);

        return response()->json($students);
    }
}
