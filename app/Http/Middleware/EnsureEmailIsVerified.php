<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is logged in and has not verified their email
        if (Auth::check() && is_null(Auth::user()->email_verified_at)) {
            Auth::logout(); // Log the user out to prevent access
            return redirect('/login')->with('error', 'You need to verify your email address before accessing this page.');
        }
    
        return $next($request);
    }
}
