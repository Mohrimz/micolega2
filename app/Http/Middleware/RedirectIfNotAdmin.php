<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has an 'admin' role
        $user = Auth::user();
        
        if ($user && $user->roles->contains('RoleName', 'admin')) {
            // If admin, proceed with the request
            return $next($request);
        }

        // If not an admin, redirect to the default dashboard or another route
        return redirect('/dashboard');  // Adjust as needed
    }
}
