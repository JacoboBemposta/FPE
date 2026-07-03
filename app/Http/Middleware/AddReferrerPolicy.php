<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddReferrerPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        return $response;
    }
}