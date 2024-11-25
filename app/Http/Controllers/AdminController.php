<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\Category;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Models\ProofDocument;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    // Show the form for adding a new skill and the list of skills
    public function index()
    {
        $categories = Category::all();
        $skills = Skill::all();
        
        $proofDocuments = ProofDocument::with(['skill', 'user'])->get();

    // Remove the debugging line (dd)
    return view('dashboard', compact('categories', 'skills', 'proofDocuments'));
    }
    

    // Method to update the status of a proof document
    public function updateProofDocumentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Find the proof document by ID
        $proofDocument = ProofDocument::findOrFail($id);
        $proofDocument->status = $request->status;
        $proofDocument->save();

        // If the status is approved, check if the user should be upgraded to peer-tutor
        if ($request->status === 'approved') {
            $userId = $proofDocument->user_id;

            // Check if the user has any approved proof documents
            $approvedCount = ProofDocument::where('user_id', $userId)->where('status', 'approved')->count();

            // If the user has at least one approved skill, add their ID to the UserRole table
            if ($approvedCount > 0) {
                // Check if the user already has the peer-tutor role
                $existingRole = UserRole::where('user_id', $userId)->where('role_id', 2)->first();

                if (!$existingRole) {
                    // If not, add the role
                    UserRole::create([
                        'user_id' => $userId,
                        'role_id' => 3, // Role ID for peer-tutor
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Proof document status updated successfully.');
    }
    //view document
    public function viewDocument($id)
{
    $proofDocument = ProofDocument::findOrFail($id);

    // Ensure the document exists
    if (!Storage::exists($proofDocument->document_path)) {
        return redirect()->back()->with('error', 'Document not found.');
    }

    // Return the document for viewing
    return Storage::response($proofDocument->document_path);
}
    // Handle the form submission to add a new skill
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        Skill::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Skill added successfully.');
    }

    // Delete a skill
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->users()->detach();

        // Delete related courses if necessary
        $skill->courses()->delete();
        $skill->delete();

        return redirect()->back()->with('success', 'Skill deleted successfully.');
    }
}
