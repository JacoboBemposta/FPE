<?php
// app/Helpers/SistemaHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SistemaHelper
{
    public static function sistemaSuscripcionesActivo()
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return true; // o false según tu lógica para usuarios no autenticados
        }
        
        $user = Auth::user();
        
        // 2. PRIMERO verificar si el usuario YA TIENE una suscripción activa
        if (self::usuarioTieneSuscripcionActiva($user)) {
            return false; // Usuario tiene suscripción → NO mostrar sistema de suscripciones
        }
        
        // 3. Solo si NO tiene suscripción, verificar si el sistema global está activo
        try {
            if (Schema::hasTable('configuraciones_sistema')) {
                $config = DB::table('configuraciones_sistema')
                    ->where('clave', 'sistema_suscripciones_activo')
                    ->value('valor');
                    
                return $config === '1'; // Sistema global activo = true, inactivo = false
            }
        } catch (\Exception $e) {
            return false;
        }
        
        return false;
    }
    
    private static function usuarioTieneSuscripcionActiva($user)
    {
        // Verificar si el usuario tiene el campo 'fin_suscripcion' en la tabla users
        // y si es mayor a la fecha actual
        if (!isset($user->fin_suscripcion) || is_null($user->fin_suscripcion)) {
            return false; // No tiene fecha de fin de suscripción
        }
        
        try {
            $fechaFin = Carbon::parse($user->fin_suscripcion);
            $hoy = Carbon::now();
            
            // Si la fecha de fin es FUTURA, la suscripción está ACTIVA
            return $fechaFin->isFuture();
            
        } catch (\Exception $e) {
            return false;
        }
    }
}