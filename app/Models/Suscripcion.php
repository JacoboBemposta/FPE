<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Suscripcion extends Model
{
    use HasFactory;

    protected $table = 'suscripciones';

    protected $fillable = [
        'user_id',
        'stripe_id',
        'stripe_subscription_id',
        'plan',
        'precio',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'cancelado_en',
        'activa', // ← Usamos 'activa' en lugar de 'estado'
        // 'intervalo' → NO LO INCLUIMOS
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'cancelado_en' => 'datetime',
        'precio' => 'decimal:2',
        'activa' => 'boolean',
        // 'metadata' → NO LO INCLUIMOS
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los pagos
     */
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    /**
     * Verificar si la suscripción está activa
     */
    public function estaActiva(): bool
    {
        return $this->activa == 1 && 
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

    /**
     * Obtener el total pagado
     */
    public function getTotalPagadoAttribute()
    {
        return $this->pagos()->where('estado', 'completado')->sum('monto_total');
    }

    /**
     * Accessor para estado (compatibilidad)
     */
    public function getEstadoAttribute()
    {
        return $this->activa ? 'activa' : 'inactiva';
    }
}