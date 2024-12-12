<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\GroupCourse;
use Illuminate\Validation\Rule;

class GroupCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        // Fetch skills with approval status for the logged-in user
        $skills = Skill::with(['proofDocuments' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get()->map(function ($skill) {
            $skill->approved = $skill->proofDocuments->contains('status', 'approved');
            return $skill;
        });
        // Fetch all group courses
        $groupCourses = GroupCourse::with('skill', 'creator')->get();

        // Pass data to the view
        return view('group_sessions.index', compact('groupCourses', 'skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'skill_id' => 'required|exists:skills,id',
        'level' => 'required|in:L4,L5,L6',
        'date' => 'required|date',
        'time' => 'required|date_format:H:i',
    ]);

    // Debug the requ

    GroupCourse::create([
        'skill_id' => $data['skill_id'],
        'level' => $data['level'],
        'date' => $data['date'],
        'time' => $data['time'],
        'created_by' => auth()->id(),
    ]);

    return redirect()->back()->with('success', 'Group course created successfully!');
}


    /**
     * Display a listing of accepted sessions.
     */
    public function acceptedSessions()
    {
        $acceptedSessions = GroupCourse::with('skill', 'creator')->get();

        return view('group_sessions.accepted', compact('acceptedSessions'));
    }
    public function joinSession($id)
    {
        $groupCourse = GroupCourse::with('skill', 'creator')->findOrFail($id);
    
        return view('group_sessions.join', compact('groupCourse'));
    }
    
public function skill()
{
    return $this->belongsTo(Skill::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
}
