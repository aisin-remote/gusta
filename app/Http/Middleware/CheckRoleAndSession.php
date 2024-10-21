<?php

namespace App\Http\Middleware;

use Closure;

class CheckRoleAndSession
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
        $user = auth()->user();
        $role = $user->role; // Assuming the 'role' field exists in the user table
        
        // Check if the user is an approver or admin
        if (in_array($role, ['approver', 'admin'])) {
            return $next($request);
        }

        // If the user is a visitor, check for session values
        if ($role === 'visitor' && $request->session()->has('company') && $request->session()->has('category')) {
            return $next($request);
        }

        // If the role does not match, redirect to an appropriate page
        return redirect('/category')->with('error', 'Access Denied.');
    }
}
