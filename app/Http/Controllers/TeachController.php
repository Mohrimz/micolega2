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
        $sessionRequests = SessionRequest::where('tutor_id', Auth::id())
            ->with(['user', 'skill',]) // Eager load user and skill relationships
            ->get();

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

    /**
     * Update the status of a session request (accept or reject).
     */
    public function updateSessionRequestStatus(Request $request, $id)
    {
        $sessionRequest = SessionRequest::findOrFail($id);

        // Validate the input
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'rejection_reason' => 'required_if:status,rejected|max:255',
        ]);

        // Update the session request
        $sessionRequest->status = $request->status;

        if ($request->status === 'rejected') {
            $sessionRequest->rejection_reason = $request->rejection_reason;
        }

        $sessionRequest->save();

        return redirect()->route('teach')->with('success', 'Session request updated successfully.');
    }
    /**
 * Submit a skill request along with proof documents.
 */
public function submitSkillRequest(Request $request)
{
    $request->validate([
        'skill_id' => 'required|exists:skills,id',
        'proof_documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $user = Auth::user();

    // Create a new SkillRequest
    $skillRequest = SkillRequest::create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Handle the uploaded proof documents
    foreach ($request->file('proof_documents') as $file) {
        $filePath = $file->store('proof_documents', 'public');

        ProofDocument::create([
            'user_id' => $user->id,
            'skill_id' => $request->input('skill_id'),
            'document_path' => $filePath,
            'status' => 'pending',
            'skill_request_id' => $skillRequest->id,
            'notes' => null, // Explicitly set to null
            'rejection_reason' => null, // Explicitly set to null
        ]);
    }

    return redirect()->back()->with('success', 'Your skill request has been submitted successfully!');
}

}
