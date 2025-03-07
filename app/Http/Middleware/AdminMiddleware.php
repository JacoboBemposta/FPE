<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        dd(Auth::user());
        // Verificamos si el usuario está autenticado y tiene el rol 'admin'
        if (Auth::check() && Auth::user()->rol === 'admin') {
            return $next($request);
        }

        // Si no es admin, aborta con código 403
        abort(403, 'Acceso denegado.');
    }
}

