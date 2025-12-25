<?php
// app/Helpers/SistemaHelper.php

namespace App\Helpers;

class SistemaHelper
{
    public static function sistemaSuscripcionesActivo()
    {
        try {
            // Verifica si existe la tabla configuraciones_sistema
            if (\Illuminate\Support\Facades\Schema::hasTable('configuraciones_sistema')) {
                $config = \Illuminate\Support\Facades\DB::table('configuraciones_sistema')
                    ->where('clave', 'sistema_suscripciones_activo')
                    ->value('valor');
                    
                return $config === '1';
            }
        } catch (\Exception $e) {
            // En caso de error, devolver false
            return false;
        }
        
        return false;
    }
}