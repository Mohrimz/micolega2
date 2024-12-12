<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionRequest;

class StudentSessionController extends Controller
{
    /**
     * Display all accepted sessions for the logged-in user.
     */
    public function index()
{
    $user = auth()->user();

    // Fetch accepted sessions where the user is either the student or the tutor
    $acceptedSessions = SessionRequest::where('status', 'accepted')
        ->where(function ($query) use ($user) {
            $query->where('tutor_id', $user->id)
                  ->orWhere('user_id', $user->id);
        })
        ->with(['skill', 'tutor', 'user']) // Eager load related models
        ->latest()
        ->get();

    return view('sessions.index', compact('acceptedSessions'));
}

    /**
     * Join a specific session.
     */
    public function joinSession($id)
    {
        $sessionRequest = SessionRequest::with(['skill', 'tutor', 'user'])->findOrFail($id);
    
        // Check if the logged-in user is part of the session
        if (auth()->id() !== $sessionRequest->tutor_id && auth()->id() !== $sessionRequest->user_id) {
            abort(403, 'Unauthorized action.');
        }
    
        return view('sessions.join', compact('sessionRequest'));
    }
}
