<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        $user = Auth::user();
        
        // Si no tiene rol, solo permitir acceso a rutas básicas
        if (!$user->rol) {
            $allowedRoutes = ['/', 'home', 'user.updateRole', 'logout'];
            
            if (!$request->is('/') && 
                !$request->routeIs('home') && 
                !$request->routeIs('user.updateRole') &&
                !$request->routeIs('logout')) {
                return redirect('/');
            }
            
            return $next($request);
        }
        
        // Verificar rol si se especificó
        if (!empty($roles) && !in_array($user->rol, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección');
        }
        
        return $next($request);
    }
}