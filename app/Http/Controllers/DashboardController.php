<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Category;
use App\Models\ProofDocument;
use App\Models\SessionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Retrieve all skills with their categories
        $skills = Skill::with('category')->get();

        // Retrieve all categories
        $categories = Category::all();

        // Retrieve all proof documents with related data
        $proofDocuments = ProofDocument::with('skill', 'user')->get();

        // Retrieve all tutors with their availabilities
        $tutors = User::role('tutor')->with('availabilities')->get();

        // Retrieve all accepted sessions for the logged-in user (students)
        $acceptedSessions = SessionRequest::where('status', 'accepted')
            ->where('user_id', $user->id)
            ->with(['skill', 'tutor', 'user'])
            ->latest()
            ->get();

        // Apply filtering logic if applicable
        if ($request->has('filter_skill')) {
            $skills = $skills->where('name', 'like', '%' . $request->input('filter_skill') . '%');
        }

        // Pass data to the view
        return view('dashboard', compact('skills', 'categories', 'proofDocuments', 'tutors', 'acceptedSessions'));
    }
}
