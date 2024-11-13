<?php

namespace App\Http\Middleware;

use Closure;

class Approver
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
        if(auth()->guest() || auth()->user()->role === 'approver' ){
            abort(403);
        }
        
        return $next($request);
    }
}
