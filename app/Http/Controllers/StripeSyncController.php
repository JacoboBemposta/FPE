<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use App\Models\Pago;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Invoice;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StripeSyncController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Sincronizar todas las suscripciones desde Stripe
     */
    public function syncAllSubscriptions()
    {
        try {
            $subscriptions = Subscription::all(['limit' => 100, 'status' => 'active']);
            
            $count = 0;
            foreach ($subscriptions as $stripeSubscription) {
                $this->syncSingleSubscription($stripeSubscription);
                $count++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sincronizadas {$count} suscripciones desde Stripe"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error syncAllSubscriptions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sincronizar una suscripción específica
     */
    private function syncSingleSubscription($stripeSubscription)
    {
        try {
            // Buscar el usuario por el customer_id de Stripe
            $customer = Customer::retrieve($stripeSubscription->customer);
            $user = User::where('email', $customer->email)->first();
            
            if (!$user) {
                Log::warning('Usuario no encontrado para customer: ' . $customer->email);
                return;
            }

            // Obtener detalles del plan
            $plan = $stripeSubscription->items->data[0]->plan ?? null;
            
            if (!$plan) {
                Log::warning('Plan no encontrado para suscripción: ' . $stripeSubscription->id);
                return;
            }

            // Convertir periodo de Stripe a formato local
            $intervalMap = [
                'month' => 'mensual',
                'year' => 'anual',
                'week' => 'semanal',
                'day' => 'diario'
            ];

            // Buscar o crear la suscripción en nuestra base de datos
            $suscripcion = Suscripcion::updateOrCreate(
                [
                    'stripe_subscription_id' => $stripeSubscription->id,
                ],
                [
                    'user_id' => $user->id,
                    'stripe_id' => $stripeSubscription->customer,
                    'plan' => $plan->nickname ?? $plan->id,
                    'tipo' => $this->determinarTipoUsuario($user),
                    'precio' => $plan->amount / 100, // Stripe usa centavos
                    'intervalo' => $intervalMap[$plan->interval] ?? $plan->interval,
                    'fecha_inicio' => Carbon::createFromTimestamp($stripeSubscription->current_period_start),
                    'fecha_fin' => Carbon::createFromTimestamp($stripeSubscription->current_period_end),
                    'cancelado_en' => $stripeSubscription->cancel_at_period_end ? 
                        Carbon::createFromTimestamp($stripeSubscription->current_period_end) : null,
                    'estado' => $stripeSubscription->status,
                    'metadata' => [
                        'stripe_plan_id' => $plan->id,
                        'stripe_price_id' => $plan->price_id ?? null,
                        'billing_cycle_anchor' => $stripeSubscription->billing_cycle_anchor,
                        'cancel_at_period_end' => $stripeSubscription->cancel_at_period_end,
                    ],
                ]
            );

            // Sincronizar los pagos de esta suscripción
            $this->syncPaymentsForSubscription($suscripcion, $stripeSubscription);
            
            Log::info('Suscripción sincronizada: ' . $suscripcion->id);
            
        } catch (\Exception $e) {
            Log::error('Error syncSingleSubscription: ' . $e->getMessage());
        }
    }

    /**
     * Sincronizar pagos de una suscripción
     */
    private function syncPaymentsForSubscription($suscripcion, $stripeSubscription)
    {
        try {
            // Obtener facturas de esta suscripción
            $invoices = Invoice::all([
                'subscription' => $stripeSubscription->id,
                'limit' => 12 // Últimos 12 meses
            ]);

            foreach ($invoices as $invoice) {
                if ($invoice->paid && $invoice->payment_intent) {
                    $this->createOrUpdatePayment($suscripcion, $invoice);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error syncPaymentsForSubscription: ' . $e->getMessage());
        }
    }

    /**
     * Crear o actualizar un pago
     */
    private function createOrUpdatePayment($suscripcion, $invoice)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($invoice->payment_intent);
            
            // Determinar fechas del periodo
            $periodStart = Carbon::createFromTimestamp($invoice->period_start);
            $periodEnd = Carbon::createFromTimestamp($invoice->period_end);
            
            Pago::updateOrCreate(
                [
                    'stripe_invoice_id' => $invoice->id,
                ],
                [
                    'suscripcion_id' => $suscripcion->id,
                    'stripe_payment_intent_id' => $invoice->payment_intent,
                    'monto' => $invoice->amount_paid / 100,
                    'moneda' => strtoupper($invoice->currency),
                    'iva' => $this->calculateIVA($invoice),
                    'monto_total' => $invoice->total / 100,
                    'fecha_inicio_periodo' => $periodStart,
                    'fecha_fin_periodo' => $periodEnd,
                    'estado' => $invoice->paid ? 'completado' : 'pendiente',
                    'metodo_pago' => $paymentIntent->payment_method_types[0] ?? 'tarjeta',
                    'fecha_pago' => $invoice->paid ? Carbon::createFromTimestamp($invoice->created) : null,
                    'user_id' => $suscripcion->user_id,
                    'datos_stripe' => json_decode($invoice, true),
                ]
            );
            
        } catch (\Exception $e) {
            Log::error('Error createOrUpdatePayment: ' . $e->getMessage());
        }
    }

    /**
     * Calcular IVA (ajusta según tu país)
     */
    private function calculateIVA($invoice)
    {
        // Por defecto 21% para España
        // Puedes ajustar esta lógica según tus necesidades
        $total = $invoice->total / 100;
        $base = $total / 1.21; // Asumiendo 21% IVA
        $iva = $total - $base;
        
        return round($iva, 2);
    }

    /**
     * Determinar tipo de usuario
     */
    private function determinarTipoUsuario($user)
    {
        return match($user->rol) {
            'profesor' => 'docente',
            'academia' => 'academia',
            default => 'otro'
        };
    }

    /**
     * Sincronizar una suscripción específica por ID
     */
    public function syncSubscription($stripeSubscriptionId)
    {
        try {
            $stripeSubscription = Subscription::retrieve($stripeSubscriptionId);
            $this->syncSingleSubscription($stripeSubscription);
            
            return response()->json([
                'success' => true,
                'message' => 'Suscripción sincronizada correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error syncSubscription: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar estado de sincronización
     */
    public function checkSyncStatus()
    {
        $localSubscriptions = Suscripcion::count();
        $localPayments = Pago::count();
        
        try {
            // Contar suscripciones activas en Stripe
            $stripeSubscriptions = Subscription::all(['limit' => 1, 'status' => 'active']);
            $stripeCount = $stripeSubscriptions->has_more ? '100+' : $stripeSubscriptions->count();
            
            return response()->json([
                'local_subscriptions' => $localSubscriptions,
                'local_payments' => $localPayments,
                'stripe_subscriptions' => $stripeCount,
                'sync_needed' => $localSubscriptions == 0 && $stripeCount > 0
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'local_subscriptions' => $localSubscriptions,
                'local_payments' => $localPayments,
                'error' => 'No se pudo conectar con Stripe: ' . $e->getMessage()
            ]);
        }
    }
}