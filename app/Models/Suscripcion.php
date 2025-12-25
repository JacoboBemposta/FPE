<?php
// app/Models/Suscripcion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suscripcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_id',
        'stripe_subscription_id',
        'plan',
        'tipo',
        'precio',
        'intervalo',
        'fecha_inicio',
        'fecha_fin',
        'cancelado_en',
        'estado',
        'metadata',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'cancelado_en' => 'datetime',
        'precio' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verificar si la suscripción está activa
     */
    public function estaActiva(): bool
    {
        return $this->estado === 'activa' && 
               $this->fecha_fin && 
               $this->fecha_fin->isFuture();
    }

    /**
     * Verificar si está por vencer (en los próximos 7 días)
     */
    public function porVencer(): bool
    {
        if (!$this->fecha_fin || !$this->estaActiva()) {
            return false;
        }
        
        return $this->fecha_fin->diffInDays(now()) <= 7;
    }
}