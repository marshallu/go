<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admins = ['bajus@marshall.edu', 'davis220@marshall.edu', 'traube3@marshall.edu', 'madden24@marshall.edu', 'cmccomas@marshall.edu'];

        if (!in_array(auth()->user(), $admins)) {
            abort(403);
        }

        return $next($request);
    }
}
