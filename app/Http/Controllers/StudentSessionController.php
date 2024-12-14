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
        $userId = auth()->id(); // Get the currently logged-in user's ID

        // Fetch accepted sessions
        $acceptedSessions = SessionRequest::where('user_id', $userId)
            ->where('status', 'accepted')
            ->with('skill', 'tutor')
            ->get();

        // Fetch rejected sessions
        $rejectedSessions = SessionRequest::where('user_id', $userId)
            ->where('status', 'rejected')
            ->with('skill', 'tutor')
            ->get();

        // Pass the data to the view
        return view('sessions.index', compact('acceptedSessions', 'rejectedSessions'));
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
