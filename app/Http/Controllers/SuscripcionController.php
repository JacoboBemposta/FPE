<?php
namespace App\Http\Controllers;

use App\Models\Suscripcion;
use App\Models\Pago;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuscripcionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Mostrar página de suscripción
     */
    public function index()
    {
        $user = Auth::user();
        
        // Solo docentes y academias pueden suscribirse
        if (!in_array($user->rol, ['profesor', 'academia'])) {
            abort(403, 'No tienes permiso para acceder a esta página');
        }
        
        // Verificar suscripción activa
        $tiene_suscripcion = false;
        $fin_suscripcion = null;
        
        if ($user->premium) {
            $suscripcionActiva = Suscripcion::where('user_id', $user->id)
                ->where('activa', 1)
                ->where('fecha_fin', '>', now())
                ->first();
            
            if ($suscripcionActiva) {
                $tiene_suscripcion = true;
                $fin_suscripcion = $suscripcionActiva->fecha_fin;
            } else {
                // Si premium está activo pero no hay suscripción activa, corregir
                $user->premium = 0;
                $user->save();
            }
        }

        return view('suscripciones.index', [
            'tiene_suscripcion' => $tiene_suscripcion,
            'fin_suscripcion' => $fin_suscripcion,
        ]);
    }

    /**
     * Crear sesión de checkout para suscripción
     */
    public function checkout(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Verificar si ya tiene suscripción activa
            $suscripcionActiva = Suscripcion::where('user_id', $user->id)
                ->where('activa', 1)
                ->where('fecha_fin', '>', now())
                ->first();
            
            if ($suscripcionActiva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una suscripción activa válida hasta ' . $suscripcionActiva->fecha_fin->format('d/m/Y')
                ], 400);
            }
            
            // Determinar el precio según el rol
            $precioId = ($user->rol === 'profesor') 
                ? env('STRIPE_PRICE_DOCENTE')  
                : env('STRIPE_PRICE_ACADEMIA'); 
            
            // Verificar que el precio existe
            try {
                $price = \Stripe\Price::retrieve($precioId);
                if (!$price->active) {
                    throw new \Exception('El precio no está activo en Stripe');
                }
            } catch (\Exception $e) {
                Log::error('Error con precio de Stripe: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error de configuración del precio. Contacta con soporte.'
                ], 500);
            }
            
            // Crear sesión de checkout
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $precioId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('suscripcion.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('suscripcion.cancel'),
                'customer_email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'rol' => $user->rol,
                ],
                'subscription_data' => [
                    'metadata' => [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                    ]
                ]
            ]);
            
            Log::info('Checkout session created', [
                'user_id' => $user->id,
                'session_id' => $checkout_session->id,
                'price_id' => $precioId
            ]);
            
            return response()->json([
                'success' => true,
                'url' => $checkout_session->url
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en checkout: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud. Por favor, intenta nuevamente.'
            ], 500);
        }
    }

    public function success(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                Log::error('No session_id provided in success callback');
                return redirect()->route('suscripciones.index')
                    ->with('error', 'No se pudo verificar la sesión de pago.');
            }
            
            Log::info('Processing success with session_id: ' . $sessionId);
            
            $user = Auth::user();
            
            // Si estamos en desarrollo y el session_id es simulado
            if (strpos($sessionId, 'simulated_') === 0) {
                // Activación simulada para desarrollo
                $fechaFin = now()->addMonth();
                
                // Crear suscripción
                $suscripcion = new Suscripcion();
                $suscripcion->user_id = $user->id;
                $suscripcion->plan = ($user->rol === 'profesor') ? 'Plan Docente Mensual' : 'Plan Academia Mensual';
                $suscripcion->precio = ($user->rol === 'profesor') ? 5 : 10;
                $suscripcion->tipo = ($user->rol === 'profesor') ? 'docente' : 'academia';
                $suscripcion->fecha_inicio = now();
                $suscripcion->fecha_fin = $fechaFin;
                $suscripcion->activa = 1;
                $suscripcion->save();
                
                // Crear pago
                $pago = new Pago();
                $pago->user_id = $user->id;
                $pago->suscripcion_id = $suscripcion->id;
                $pago->monto = ($user->rol === 'profesor') ? 5 : 10;
                $pago->moneda = 'EUR';
                $pago->iva = 21.00;
                $pago->monto_total = ($user->rol === 'profesor') ? 5 : 10;
                $pago->fecha_inicio_periodo = now();
                $pago->fecha_fin_periodo = $fechaFin;
                $pago->estado = 'completado';
                $pago->metodo_pago = 'tarjeta';
                $pago->fecha_pago = now();
                $pago->save();
                
                // Actualizar usuario
                $user->inicio_suscripcion = now();
                $user->fin_suscripcion = $fechaFin;
                $user->premium = 1;
                $user->save();
                
                return redirect()->route('suscripciones.index')
                    ->with('success', '¡Suscripción activada con éxito! Acceso premium habilitado hasta ' . $fechaFin->format('d/m/Y'));
            }
            
            // Para producción: recuperar la sesión de Stripe
            $session = Session::retrieve($sessionId);
            
            // Verificar que el user_id en metadata coincide
            if (!isset($session->metadata->user_id) || $session->metadata->user_id != $user->id) {
                Log::error('User ID mismatch', [
                    'session_user_id' => $session->metadata->user_id ?? 'none',
                    'auth_user_id' => $user->id
                ]);
                
                return redirect()->route('suscripciones.index')
                    ->with('error', 'Error de autenticación en la sesión de pago.');
            }
            
            // Si el pago está pendiente, solo crear registro pendiente
            if ($session->payment_status !== 'paid') {
                Log::warning('Payment not completed', [
                    'session_id' => $sessionId,
                    'payment_status' => $session->payment_status
                ]);
                
                // Crear registro pendiente
                $suscripcionPendiente = new Suscripcion();
                $suscripcionPendiente->user_id = $user->id;
                $suscripcionPendiente->stripe_id = $session->customer;
                $suscripcionPendiente->stripe_subscription_id = $session->subscription;
                $suscripcionPendiente->plan = ($user->rol === 'profesor') ? 'Plan Docente' : 'Plan Academia';
                $suscripcionPendiente->precio = ($user->rol === 'profesor') ? 5 : 10;
                $suscripcionPendiente->tipo = ($user->rol === 'profesor') ? 'docente' : 'academia';
                $suscripcionPendiente->fecha_inicio = now();
                $suscripcionPendiente->fecha_fin = now()->addMonth();
                $suscripcionPendiente->activa = 0; // Pendiente
                $suscripcionPendiente->save();
                
                return redirect()->route('suscripciones.index')
                    ->with('info', 'Estamos procesando tu pago. Recibirás una confirmación cuando se complete.');
            }
            
            // Si el pago está pagado, activar suscripción
            $subscriptionId = $session->subscription;
            
            if (!$subscriptionId) {
                Log::error('No subscription ID in session', ['session_id' => $sessionId]);
                return redirect()->route('suscripciones.index')
                    ->with('error', 'No se pudo obtener la información de suscripción.');
            }
            
            $subscription = Subscription::retrieve($subscriptionId);
            
            // Verificar que la suscripción tiene los datos necesarios
            if (!isset($subscription->current_period_start) || !isset($subscription->current_period_end)) {
                Log::warning('Subscription missing period dates, using defaults', [
                    'subscription_id' => $subscriptionId
                ]);
                $startDate = now();
                $endDate = now()->addMonth();
            } else {
                $startDate = Carbon::createFromTimestamp($subscription->current_period_start);
                $endDate = Carbon::createFromTimestamp($subscription->current_period_end);
            }
            
            // Crear/actualizar suscripción
            $suscripcion = Suscripcion::updateOrCreate(
                ['stripe_subscription_id' => $subscriptionId],
                [
                    'user_id' => $user->id,
                    'stripe_id' => $session->customer,
                    'stripe_subscription_id' => $subscriptionId,
                    'plan' => ($user->rol === 'profesor') ? 'Plan Docente' : 'Plan Academia',
                    'precio' => ($user->rol === 'profesor') ? 5 : 10,
                    'tipo' => ($user->rol === 'profesor') ? 'docente' : 'academia',
                    'fecha_inicio' => $startDate,
                    'fecha_fin' => $endDate,
                    'activa' => 1,
                ]
            );
            
            // Actualizar usuario
            $user->inicio_suscripcion = $startDate->toDateString();
            $user->fin_suscripcion = $endDate->toDateString();
            $user->premium = 1;
            $user->save();
            
            Log::info('User subscription updated', [
                'user_id' => $user->id,
                'subscription_id' => $subscriptionId,
                'end_date' => $endDate
            ]);
            
            return redirect()->route('suscripciones.index')
                ->with('success', '¡Suscripción activada con éxito! Tu acceso premium es válido hasta ' . $endDate->format('d/m/Y'));
                
        } catch (\Exception $e) {
            Log::error('Error en success: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('suscripciones.index')
                ->with('error', 'Hubo un problema al activar tu suscripción. ' . $e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        return redirect()->route('suscripciones.index')
            ->with('info', 'La suscripción fue cancelada. Puedes intentar nuevamente cuando quieras.');
    }

    /**
     * Ver detalles de la suscripción actual
     */
    public function detalles()
    {
        $user = Auth::user();
        
        // Obtener suscripción activa
        $suscripcionActiva = Suscripcion::where('user_id', $user->id)
            ->where('activa', 1)
            ->where('fecha_fin', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();
        
        $pagos = collect();
        if ($suscripcionActiva) {
            $pagos = Pago::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        $tiene_suscripcion = $suscripcionActiva ? true : false;
        
        return view('suscripciones.detalles', [
            'suscripcion' => $suscripcionActiva,
            'pagos' => $pagos,
            'tiene_suscripcion' => $tiene_suscripcion,
        ]);
    }

    /**
     * Cancelar suscripción
     */
    public function cancelarSuscripcion(Request $request)
    {
        $user = Auth::user();
        
        // Buscar suscripción activa
        $suscripcion = Suscripcion::where('user_id', $user->id)
            ->where('activa', 1)
            ->where('fecha_fin', '>', now())
            ->first();
        
        if (!$suscripcion) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes una suscripción activa'
            ], 400);
        }
        
        try {
            // Cancelar en Stripe si existe stripe_subscription_id
            if ($suscripcion->stripe_subscription_id) {
                try {
                    $stripeSubscription = Subscription::retrieve($suscripcion->stripe_subscription_id);
                    $stripeSubscription->cancel();
                    
                    $fechaFin = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
                } catch (\Exception $e) {
                    Log::warning('Error al cancelar en Stripe: ' . $e->getMessage());
                    $fechaFin = now(); // Cancelar inmediatamente
                }
            } else {
                $fechaFin = now(); // Cancelar inmediatamente
            }
            
            // Actualizar suscripción
            $suscripcion->activa = 0;
            $suscripcion->cancelado_en = now();
            $suscripcion->fecha_fin = $fechaFin;
            $suscripcion->save();
            
            // Actualizar usuario
            $user->premium = 0;
            $user->save();
            
            Log::info('Suscripción cancelada', [
                'user_id' => $user->id,
                'suscripcion_id' => $suscripcion->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Suscripción cancelada correctamente',
                'fin_suscripcion' => $fechaFin->format('d/m/Y')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error cancelando suscripción: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook de Stripe
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        
        Log::info('Webhook recibido', ['content_length' => strlen($payload)]);
        
        try {
            if (empty($endpointSecret)) {
                throw new \Exception('STRIPE_WEBHOOK_SECRET no configurado');
            }
            
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
            
            Log::info('Evento Stripe recibido', [
                'type' => $event->type,
                'id' => $event->id
            ]);
            
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->handleCheckoutSessionCompleted($event->data->object);
                    break;
                    
                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;
                    
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;
                    
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;
                    
                default:
                    Log::info('Evento no manejado: ' . $event->type);
            }
            
            return response()->json(['received' => true]);
            
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Firma de webhook inválida', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Error en webhook', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Checkout session completed', [
            'session_id' => $session->id,
            'customer' => $session->customer,
            'subscription' => $session->subscription,
            'payment_status' => $session->payment_status
        ]);
        
        if (!isset($session->metadata->user_id)) {
            Log::error('No user_id en metadata del session');
            return;
        }
        
        $userId = $session->metadata->user_id;
        $user = User::find($userId);
        
        if (!$user) {
            Log::error('Usuario no encontrado', ['user_id' => $userId]);
            return;
        }
        
        // Actualizar stripe_id en usuario si no existe
        if ($session->customer && empty($user->stripe_id)) {
            $user->stripe_id = $session->customer;
            $user->save();
        }
        
        Log::info('Checkout session procesado', ['user_id' => $user->id]);
    }

    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Subscription updated/created', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
            'customer' => $subscription->customer
        ]);
        
        try {
            // Buscar usuario por stripe_id
            $user = User::where('stripe_id', $subscription->customer)->first();
            
            if (!$user) {
                Log::warning('Usuario no encontrado para subscription', [
                    'customer' => $subscription->customer
                ]);
                return;
            }
            
            // Verificar que tenemos period dates
            if (!isset($subscription->current_period_start) || !isset($subscription->current_period_end)) {
                Log::warning('Subscription missing period dates', [
                    'subscription_id' => $subscription->id
                ]);
                return;
            }
            
            // Calcular fechas
            $startDate = Carbon::createFromTimestamp($subscription->current_period_start);
            $endDate = Carbon::createFromTimestamp($subscription->current_period_end);
            
            // Determinar plan basado en el precio
            $plan = 'Plan Academia';
            $tipo = 'academia';
            $precio = 10;
            
            if (isset($subscription->items->data[0]->price->unit_amount)) {
                $amount = $subscription->items->data[0]->price->unit_amount / 100;
                if ($amount == 5) {
                    $plan = 'Plan Docente';
                    $tipo = 'docente';
                    $precio = 5;
                }
            }
            
            // Crear/actualizar suscripción
            $suscripcion = Suscripcion::updateOrCreate(
                ['stripe_subscription_id' => $subscription->id],
                [
                    'user_id' => $user->id,
                    'stripe_id' => $subscription->customer,
                    'stripe_subscription_id' => $subscription->id,
                    'plan' => $plan,
                    'precio' => $precio,
                    'tipo' => $tipo,
                    'fecha_inicio' => $startDate,
                    'fecha_fin' => $endDate,
                    'activa' => $subscription->status === 'active' ? 1 : 0,
                ]
            );
            
            // Actualizar usuario si la suscripción está activa
            if ($subscription->status === 'active') {
                $user->inicio_suscripcion = $startDate->toDateString();
                $user->fin_suscripcion = $endDate->toDateString();
                $user->premium = 1;
                $user->save();
            }
            
            Log::info('Suscripción actualizada por webhook', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'status' => $subscription->status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en handleSubscriptionUpdated: ' . $e->getMessage());
        }
    }

    private function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'subscription' => $invoice->subscription,
            'amount_paid' => $invoice->amount_paid
        ]);

        try {
            if (!$invoice->subscription) {
                return;
            }

            // Buscar suscripción
            $suscripcion = Suscripcion::where('stripe_subscription_id', $invoice->subscription)->first();
            
            if (!$suscripcion) {
                Log::warning('Suscripción no encontrada para invoice');
                return;
            }

            // Crear registro de pago
            $pago = new Pago();
            $pago->suscripcion_id = $suscripcion->id;
            $pago->user_id = $suscripcion->user_id;
            $pago->stripe_invoice_id = $invoice->id;
            $pago->stripe_payment_intent_id = $invoice->payment_intent;
            $pago->monto = $invoice->amount_paid / 100;
            $pago->moneda = strtoupper($invoice->currency);
            $pago->iva = $this->calculateIVA($invoice);
            $pago->monto_total = $invoice->total / 100;
            $pago->fecha_inicio_periodo = Carbon::createFromTimestamp($invoice->period_start);
            $pago->fecha_fin_periodo = Carbon::createFromTimestamp($invoice->period_end);
            $pago->estado = 'completado';
            $pago->metodo_pago = 'tarjeta';
            $pago->fecha_pago = Carbon::createFromTimestamp($invoice->created);
            $pago->datos_stripe = json_encode($invoice);
            $pago->save();

            Log::info('Pago registrado', [
                'invoice_id' => $invoice->id,
                'monto' => $invoice->amount_paid / 100
            ]);

        } catch (\Exception $e) {
            Log::error('Error en handleInvoicePaymentSucceeded: ' . $e->getMessage());
        }
    }

    /**
     * Calcular IVA
     */
    private function calculateIVA($invoice)
    {
        $total = $invoice->total / 100;
        $base = $total / 1.21; // 21% IVA para España
        return round($total - $base, 2);
    }

    /**
     * Procesar suscripción eliminada
     */
    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Subscription deleted', ['subscription_id' => $subscription->id]);

        try {
            $suscripcion = Suscripcion::where('stripe_subscription_id', $subscription->id)->first();
            
            if ($suscripcion) {
                $suscripcion->activa = 0;
                $suscripcion->cancelado_en = now();
                $suscripcion->fecha_fin = Carbon::createFromTimestamp($subscription->current_period_end);
                $suscripcion->save();
                
                // Actualizar usuario
                $user = $suscripcion->user;
                if ($user) {
                    $user->premium = 0;
                    $user->save();
                }
                
                Log::info('Suscripción marcada como cancelada', [
                    'suscripcion_id' => $suscripcion->id,
                    'user_id' => $suscripcion->user_id
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error en handleSubscriptionDeleted: ' . $e->getMessage());
        }
    }

    public function testSuccess(Request $request)
    {
        $user = Auth::user();
        
        // Simular suscripción exitosa
        $suscripcion = new Suscripcion();
        $suscripcion->user_id = $user->id;
        $suscripcion->plan = $user->rol === 'profesor' ? 'Plan Docente Mensual' : 'Plan Academia Mensual';
        $suscripcion->precio = $user->rol === 'profesor' ? 5 : 10;
        $suscripcion->tipo = $user->rol === 'profesor' ? 'docente' : 'academia';
        $suscripcion->fecha_inicio = now();
        $suscripcion->fecha_fin = now()->addMonth();
        $suscripcion->activa = 1;
        $suscripcion->save();
        
        // Crear pago de prueba
        $pago = new Pago();
        $pago->user_id = $user->id;
        $pago->suscripcion_id = $suscripcion->id;
        $pago->monto = $user->rol === 'profesor' ? 5 : 10;
        $pago->moneda = 'EUR';
        $pago->iva = 21.00;
        $pago->monto_total = $user->rol === 'profesor' ? 5 : 10;
        $pago->fecha_inicio_periodo = now();
        $pago->fecha_fin_periodo = now()->addMonth();
        $pago->estado = 'completado';
        $pago->metodo_pago = 'tarjeta';
        $pago->fecha_pago = now();
        $pago->save();
        
        // Actualizar usuario
        $user->inicio_suscripcion = now();
        $user->fin_suscripcion = now()->addMonth();
        $user->premium = 1;
        $user->save();
        
        return redirect()->route('suscripciones.detalles')
            ->with('success', 'Suscripción de prueba activada');
    }

    public function probarSuscripcion(Request $request)
    {
        $user = Auth::user();
        
        // Crear suscripción de prueba (sin Stripe)
        $suscripcion = new Suscripcion();
        $suscripcion->user_id = $user->id;
        $suscripcion->plan = $user->rol === 'profesor' ? 'Plan Docente' : 'Plan Academia';
        $suscripcion->precio = $user->rol === 'profesor' ? 5 : 10;
        $suscripcion->tipo = $user->rol === 'profesor' ? 'docente' : 'academia';
        $suscripcion->fecha_inicio = now();
        $suscripcion->fecha_fin = now()->addMonth();
        $suscripcion->activa = 1;
        $suscripcion->save();
        
        // Crear pago de prueba
        $pago = new Pago();
        $pago->user_id = $user->id;
        $pago->suscripcion_id = $suscripcion->id;
        $pago->monto = $user->rol === 'profesor' ? 5 : 10;
        $pago->moneda = 'EUR';
        $pago->iva = 21.00;
        $pago->monto_total = $user->rol === 'profesor' ? 5 : 10;
        $pago->fecha_inicio_periodo = now();
        $pago->fecha_fin_periodo = now()->addMonth();
        $pago->estado = 'completado';
        $pago->metodo_pago = 'tarjeta';
        $pago->fecha_pago = now();
        $pago->save();
        
        // Actualizar usuario
        $user->inicio_suscripcion = now();
        $user->fin_suscripcion = now()->addMonth();
        $user->premium = 1;
        $user->save();
        
        return redirect()->route('suscripciones.detalles')
            ->with('success', 'Suscripción de prueba creada');
    }
}