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
        
        if (Auth::check()) {
            $user = Auth::user();

            // Si el usuario no tiene rol, mostrar el modal
            if (is_null($user->rol)) {
                session(['show_role_modal' => true]);
                
                // Si está intentando acceder a rutas protegidas sin rol, redirigir a home
                if ($request->is('academia/*') || $request->is('profesor/*') || $request->is('alumno/*') || $request->is('admin/*')) {
                    return redirect('/')->with('error', 'Debes seleccionar un rol primero');
                }
            }
            // Si el usuario TIENE rol, verificar que pueda acceder a la ruta
            else {
                $rol = $user->rol;
                $ruta = $request->path();
                
                // Verificar acceso basado en rol
                if (str_starts_with($ruta, 'admin/') && $rol !== 'admin') {
                    abort(403, 'No tienes permisos de administrador');
                }
                
                if (str_starts_with($ruta, 'academia/') && $rol !== 'academia') {
                    abort(403, 'Acceso reservado para academias');
                }
                
                if (str_starts_with($ruta, 'profesor/') && $rol !== 'profesor') {
                    abort(403, 'Acceso reservado para profesores');
                }
                
                if (str_starts_with($ruta, 'alumno/') && $rol !== 'alumno') {
                    abort(403, 'Acceso reservado para alumnos');
                }
            }
        }

        return $next($request);
    }
}