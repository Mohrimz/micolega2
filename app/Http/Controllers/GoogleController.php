<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // Get the user's information from Google
        $user = Socialite::driver('google')->user();

        // Find or create a user based on their Google account info
        $findUser = User::where('google_id', $user->id)->first();

        if ($findUser) {
            // If user already exists, log them in
            Auth::login($findUser);
        } else {
            // If user doesn't exist, create a new user
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => null, // No password needed for Google OAuth
            ]);
            Auth::login($newUser);
        }

        // Redirect to the courses page (or wherever you want to send them)
        return redirect()->route('courses.index')->with('success', 'Google Account Linked!');
    }
}
