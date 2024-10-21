<?php

namespace App\Http\Middleware;

use Closure;

class CheckCompany
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
        // Check if the 'company_type' exists in the session (set during selection in /portal)
        if (!$request->session()->has('company')) {
            // Redirect back to the portal if no company type is selected
            return redirect('/portal');
        }

        return $next($request);
    }
}
