<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionRequest;
use App\Models\Skill;
use App\Models\GroupCourse;
use Illuminate\Support\Facades\DB;


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
    public function showGroupSessions(Request $request)
    {
        $userId = auth()->id(); // Get the logged-in user's ID
        $currentDateTime = now();

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
        $groupCourses = $query->with(['skill', 'creator', 'users'])->get();

        // For students, fetch rejected sessions where they were enrolled
        foreach ($groupCourses as $course) {
            if ($course->status === 'removed') {
                $course->visibleToStudent = $course->users->contains($userId); // Mark if the course is visible to the current student
            }
        }

        // Get the approved skills for the logged-in user from the proof_documents table
        $approvedSkillIds = DB::table('proof_documents')
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->pluck('skill_id');

        // Fetch student availabilities filtered by approved skills
        $studentAvailabilities = DB::table('availabilities')
            ->join('availability_user', 'availabilities.id', '=', 'availability_user.availability_id')
            ->join('skill_user', 'availability_user.user_id', '=', 'skill_user.user_id')
            ->join('skills', 'skill_user.skill_id', '=', 'skills.id')
            ->whereIn('skills.id', $approvedSkillIds) // Filter by approved skill IDs
            ->select(
                'skills.name as skill_name',
                'availabilities.date',
                'availabilities.time',
                DB::raw('COUNT(availability_user.user_id) as student_count')
            )
            ->groupBy('skills.name', 'availabilities.date', 'availabilities.time')
            ->orderBy('availabilities.date')
            ->orderBy('availabilities.time')
            ->get();

        // Return the view with group courses, skills, approvedSkillIds, and student availabilities
        return view('group_sessions.index', compact('groupCourses', 'skills', 'studentAvailabilities', 'approvedSkillIds'));
    }

    public function removeSession(Request $request, $id)
    {
        $groupCourse = GroupCourse::findOrFail($id);

        // Ensure only the creator can cancel the session
        if (auth()->id() !== $groupCourse->created_by) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the rejection reason
        $request->validate([
            'reject_reason' => 'required|string|max:255',
        ]);

        // Save the rejection reason and mark the session as removed
        $groupCourse->reject_reason = $request->input('reject_reason');
        $groupCourse->status = 'removed'; // Update the status to 'removed'
        $groupCourse->save();

        return redirect()->route('group-sessions')->with('success', 'Session removed successfully with a reason.');
    }
public function enroll(Request $request, $id)
{
    $groupCourse = GroupCourse::findOrFail($id);
    $userId = auth()->id();

    if ($groupCourse->users()->where('user_id', $userId)->exists()) {
        // Exclude the user
        $groupCourse->users()->detach($userId);
        return redirect()->back()->with('success', 'You have been excluded from the session.');
    } else {
        // Enroll the user
        $groupCourse->users()->attach($userId);
        return redirect()->back()->with('success', 'You have enrolled in the session.');
    }
}




    


}
