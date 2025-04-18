<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!in_array(auth()->user()->rol, $roles)) {
            return redirect('/');
        }
    
        return $next($request);
    }
}
