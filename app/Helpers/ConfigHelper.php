<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfigHelper
{
    /**
     * Obtener el estado del sistema de suscripciones
     */
    public static function sistemaSuscripcionesActivo()
    {
        try {
            // Usar caché por 5 minutos para no hacer consultas constantes
            return Cache::remember('sistema_suscripciones_activo', 300, function () {
                $valor = DB::table('configuraciones_sistema')
                    ->where('clave', 'sistema_suscripciones_activo')
                    ->value('valor');
                
                return $valor === '1' || $valor === 1;
            });
        } catch (\Exception $e) {
            // Si hay error, retornar false
            return false;
        }
    }
    
    /**
     * Actualizar el estado del sistema
     */
    public static function actualizarSistemaSuscripciones($activo)
    {
        try {
            DB::table('configuraciones_sistema')
                ->where('clave', 'sistema_suscripciones_activo')
                ->update(['valor' => $activo ? '1' : '0']);
            
            // Limpiar caché
            Cache::forget('sistema_suscripciones_activo');
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}