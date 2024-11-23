<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Category;
use App\Models\ProofDocument;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $skills = Skill::query()->with('category')->get();
        $categories = Category::all();
        $proofDocuments = ProofDocument::with('skill', 'user')->get();
        $tutors = User::role('tutor')->with('availabilities')->get();

        // Filtering logic (if applicable)
        if ($request->has('filter_skill')) {
            $skills = $skills->where('name', 'like', '%' . $request->input('filter_skill') . '%');
        }

        return view('dashboard', compact('skills', 'categories', 'proofDocuments', 'tutors'));
    }
}

