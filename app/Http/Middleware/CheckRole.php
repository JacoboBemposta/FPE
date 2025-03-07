<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $rol)
    {
        if (!Auth::check() || Auth::user()->rol !== $rol) {
            return redirect('/')->with('error', 'Acceso no autorizado.');
        }
        return $next($request);
    }
}
