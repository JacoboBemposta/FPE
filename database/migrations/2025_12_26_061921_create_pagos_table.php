<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            
            // Relación con suscripción (ya tienes esta tabla)
            $table->unsignedBigInteger('suscripcion_id');
            $table->foreign('suscripcion_id')->references('id')->on('suscripciones')->onDelete('cascade');
            
            // Información del pago
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->decimal('monto', 10, 2);
            $table->string('moneda', 3)->default('EUR');
            $table->decimal('iva', 5, 2)->default(21.00);
            $table->decimal('monto_total', 10, 2);
            
            // Periodo cubierto
            $table->date('fecha_inicio_periodo');
            $table->date('fecha_fin_periodo');
            
            // Estado y método de pago
            $table->enum('estado', ['pendiente', 'completado', 'fallido', 'reembolsado', 'cancelado'])->default('completado');
            $table->string('metodo_pago')->default('tarjeta');
            
            // Fechas
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            
            // Información del usuario
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Para academias que pagan por sus docentes
            $table->unsignedBigInteger('academia_id')->nullable();
            $table->foreign('academia_id')->references('id')->on('users')->onDelete('set null');
            
            // Metadata
            $table->json('datos_stripe')->nullable();
            $table->text('notas')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('estado');
            $table->index('fecha_pago');
            $table->index(['fecha_inicio_periodo', 'fecha_fin_periodo']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};