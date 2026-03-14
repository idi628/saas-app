<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is logged in AND their is_admin flag is true, let them in!
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Otherwise, kick them out with a 403 Forbidden error
        abort(403, 'Unauthorized. Only the SaaS Owner can access this area.');
    }
}