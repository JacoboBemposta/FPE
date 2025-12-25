<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfiguracionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Primero registramos un binding para la configuración
        $this->app->bind('configuracion.sistema', function ($app) {
            return new class {
                public function get($clave, $default = null)
                {
                    // Intentamos obtener de la caché primero
                    return Cache::remember('configuracion_' . $clave, 3600, function () use ($clave, $default) {
                        try {
                            // Verificamos si la aplicación ya está booteada
                            if (app()->isBooted()) {
                                // Usamos DB facade directamente aquí
                                $resultado = DB::table('configuraciones_sistema')
                                    ->where('clave', $clave)
                                    ->first();
                                
                                return $resultado ? $resultado->valor : $default;
                            }
                        } catch (\Exception $e) {
                            // Si hay error, retornamos el valor por defecto
                        }
                        
                        return $default;
                    });
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Este código se ejecutará después de que todos los providers estén registrados
        // pero solo si no estamos en consola
        if (!$this->app->runningInConsole()) {
            // Usamos un evento para asegurarnos de que la base de datos esté lista
            $this->app->booted(function () {
                try {
                    // Obtenemos el valor de la configuración
                    $configuracion = app('configuracion.sistema');
                    $sistemaActivo = $configuracion->get('sistema_suscripciones_activo', '0') === '1';
                    
                    // Establecemos en la configuración de Laravel
                    config(['app.sistema_suscripciones_activo' => $sistemaActivo]);
                    
                } catch (\Exception $e) {
                    // En caso de error, usar valor por defecto
                    config(['app.sistema_suscripciones_activo' => false]);
                }
            });
        }
    }
}