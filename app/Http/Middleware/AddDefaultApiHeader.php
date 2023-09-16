<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class AddDefaultApiHeader
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next)
    {
        // add default Accept header
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
