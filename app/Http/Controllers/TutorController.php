<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupSessionRequest;
use Illuminate\Support\Facades\DB;

class TutorController extends Controller
{
    // Method to handle group session request
    public function requestGroupSession(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'reason' => 'required|string|max:1000',
        'member_emails' => 'required|string', // Comma-separated emails
    ]);

    // Create the group session request
    $groupSessionRequest = GroupSessionRequest::create([
        'reason' => $validated['reason'],
        'member_emails' => $validated['member_emails'],
    ]);

    // Return success response
    return redirect()->back()->with('success', 'Group session request submitted successfully.');
}
}