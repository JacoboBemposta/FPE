<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Solo verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            

            
            // Si el usuario no tiene rol, mostrar el modal
            if (is_null($user->rol)) {
                session(['show_role_modal' => true]);
                
                // Si está intentando acceder a rutas protegidas sin rol, redirigir a home
                if ($request->is('academia/*') || $request->is('profesor/*')) {
                    return redirect('/home');
                }
                if ($request->is('admin/*') && $user->rol !== 'admin') {
                    return redirect('/home')->with('error', 'No tienes permisos de administrador');
                }
            }
        }

        return $next($request);
    }
}