<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionRequest;
use App\Models\Skill;
use App\Models\GroupCourse;


class SessionController extends Controller
{
    /**
     * Display the accepted sessions for the logged-in user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        // Retrieve all accepted sessions for the logged-in user (tutor or student)
        $approvedSessions = SessionRequest::where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('tutor_id', $user->id)
                      ->orWhere('user_id', $user->id);
            })
            ->with(['skill', 'tutor', 'user']) // Load related models for efficiency
            ->latest()
            ->get();

        // Return the view with the approved sessions
        return view('sessions.index', compact('approvedSessions'));
    }

    /**
     * Join a specific session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function joinSession($id)
    {
        $sessionRequest = SessionRequest::with(['skill', 'tutor', 'user'])->findOrFail($id);

        // Check if the logged-in user is part of the session
        if (auth()->id() !== $sessionRequest->tutor_id && auth()->id() !== $sessionRequest->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Return the view to join the session
        return view('sessions.join', compact('sessionRequest'));
    }

    /**
     * Cancel a specific session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelSession($id)
    {
        $sessionRequest = SessionRequest::findOrFail($id);

        // Check if the logged-in user is part of the session
        if (auth()->id() !== $sessionRequest->tutor_id && auth()->id() !== $sessionRequest->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Update the status to canceled
        $sessionRequest->update(['status' => 'canceled']);

        // Redirect back to the sessions page with a success message
        return redirect()->route('sessions.index')->with('success', 'Session cancelled successfully.');
    }
     /**
     * Display the filtered group courses based on skill and level.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function filterGroupCourses(Request $request)
    {
        // Fetch all skills for the filter dropdown
        $skills = Skill::all();

        // Base query for group courses
        $query = GroupCourse::query();

        // Apply filters if they are provided
        if ($request->has('skill_id') && $request->skill_id) {
            $query->where('skill_id', $request->skill_id);
        }

        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
        }

        // Fetch filtered or all group courses
        $groupCourses = $query->with(['skill', 'creator'])->get();

        // Return the view with the filtered group courses
        return view('group_sessions.index', compact('groupCourses', 'skills'));
    }
}
