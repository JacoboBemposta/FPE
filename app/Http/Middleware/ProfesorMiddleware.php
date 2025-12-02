<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (!$user->rol) {
            return redirect('/home')->with('error', 'Debes seleccionar un rol primero');
        }
        
        if ($user->rol !== 'profesor') {
            abort(403, 'Acceso reservado para profesores');
        }

        return $next($request);
    }
}