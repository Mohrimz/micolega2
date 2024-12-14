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
    public function submitSkillRequest(Request $request)
{
    // Validate the input data
    $request->validate([
        'proof' => 'required|array', // Ensure the 'proof' field is an array
        'proof.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048', // Validate each file
        'skill_id' => 'required|exists:skills,id', // Validate the skill_id exists in the skills table
    ]);

    // Process each uploaded file
    foreach ($request->file('proof') as $file) {
        // Store the file in the 'proof_documents' directory
        $filePath = $file->store('proof_documents', 'public');

        // Save the file information in the database
        ProofDocument::create([
            'user_id' => Auth::id(), // ID of the logged-in user
            'skill_id' => $request->skill_id, // Skill ID from the form
            'document_path' => $filePath, // Path to the uploaded file
            'status' => 'pending', // Default status
        ]);
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Proof documents uploaded successfully!');
}
}
