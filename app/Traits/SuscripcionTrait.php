<?php

namespace App\Traits;

trait SuscripcionTrait
{
    /**
     * Verificar si tiene suscripción activa
     */
    public function tieneSuscripcionActiva(): bool
    {
        // Si no es premium, no tiene suscripción activa
        if (!$this->premium) {
            return false;
        }
        
        // Si no tiene fecha fin, no tiene suscripción activa
        if (!$this->fin_suscripcion) {
            return false;
        }
        
        // Verificar que la fecha fin sea futura
        try {
            return $this->fin_suscripcion->isFuture();
        } catch (\Exception $e) {
            \Log::error('Error verificando fecha de suscripción: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Activar suscripción por un mes
     */
    public function activarSuscripcionMensual(string $plan): void
    {
        $this->inicio_suscripcion = now();
        $this->fin_suscripcion = now()->addMonth();
        $this->premium = true;
        $this->save();
    }
    
    /**
     * Verificar si necesita renovar
     */
    public function necesitaRenovarSuscripcion(): bool
    {
        return !$this->tieneSuscripcionActiva();
    }
}