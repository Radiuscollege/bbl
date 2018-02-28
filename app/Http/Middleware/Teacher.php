<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Teacher
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
        if (Auth::check() && Auth::user()->type == "student") {
            return response('Alleen toegankelijk voor docenten.', 401);
        }
        
        return $next($request);
    }
}
