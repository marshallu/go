<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreatedByCanEditUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		$admins = ['bajus', 'davis220', 'traube3', 'madden24', 'cmccomas'];

		if (in_array(cas()->user(), $admins)) {
			return $next($request);
		}

		if (cas()->user() !== $request->url->created_by) {
			abort(403);
		}

        return $next($request);
    }
}
