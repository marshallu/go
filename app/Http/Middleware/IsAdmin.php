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
		$admins = ['bajus', 'davis220', 'traube3', 'madden24', 'cmccomas'];

		if (!in_array(cas()->user(), $admins)) {
			abort(403);
		}

		return $next($request);
    }
}
