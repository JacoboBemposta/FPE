<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        $user = Auth::user();
        
        // Verificar si el usuario tiene rol
        if (!$user->rol) {
            session(['show_role_modal' => true]);
            return redirect('/')->with('error', 'Debes seleccionar un rol primero');
        }
        
        // Verificar si el rol del usuario está en los permitidos
        if (!in_array($user->rol, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección');
        }
    
        return $next($request);
    }
}