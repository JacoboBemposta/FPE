<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica que el usuario esté autenticado y sea admin
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}
