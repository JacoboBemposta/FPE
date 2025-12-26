<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    // Si tu tabla se llama 'pagos' (en español)
    protected $table = 'pagos';

    protected $fillable = [
        'suscripcion_id',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'monto',
        'moneda',
        'iva',
        'monto_total',
        'fecha_inicio_periodo',
        'fecha_fin_periodo',
        'estado',
        'metodo_pago',
        'fecha_pago',
        'fecha_vencimiento',
        'user_id',
        'academia_id',
        'datos_stripe',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'iva' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'fecha_inicio_periodo' => 'date',
        'fecha_fin_periodo' => 'date',
        'fecha_pago' => 'datetime',
        'fecha_vencimiento' => 'datetime',
        'datos_stripe' => 'array',
    ];

    /**
     * Relación con la suscripción
     */
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}