<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSuscripcion
{
    public function handle(Request $request, Closure $next)
    {
        // Si el sistema de suscripciones está desactivado, permitir acceso
        if (!config('app.sistema_suscripciones_activo')) {
            return $next($request);
        }

        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar si el usuario tiene suscripción activa
        if (!$user->tieneSuscripcionActiva()) {
            // Para API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Suscripción requerida',
                    'requires_subscription' => true
                ], 403);
            }
            
            // Para web, redirigir o mostrar modal
            if ($request->isMethod('get')) {
                return redirect()->route('suscripcion.required')
                    ->with('warning', 'Necesitas una suscripción activa para contactar.');
            }
            
            return back()->with('error', 'Necesitas una suscripción activa para esta acción.');
        }

        return $next($request);
    }
}