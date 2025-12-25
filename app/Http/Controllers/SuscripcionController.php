<?php
// app/Http/Controllers/SuscripcionController.php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class SuscripcionController extends Controller
{
    public function __construct()
    {
        // Configurar Stripe
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Mostrar planes de suscripción
     */
    public function planes()
    {
        $user = Auth::user();
        $tipoUsuario = $user->rol; // 'profesor' o 'academia'
        
        // Validar que el usuario tenga un rol válido para suscribirse
        if (!in_array($tipoUsuario, ['profesor', 'academia'])) {
            abort(403, 'No tienes permiso para acceder a esta página');
        }
        
        return view('suscripciones.planes', [
            'tipo_usuario' => $tipoUsuario,
            'user' => $user
        ]);
    }

    /**
     * Checkout para un plan específico
     */
    public function checkout(Request $request, string $plan)
    {
        $user = Auth::user();
        $tipoUsuario = $user->rol; // 'profesor' o 'academia'
        
        // Validar que el usuario pueda acceder a este plan
        if (($tipoUsuario === 'profesor' && $plan !== 'docente') || 
            ($tipoUsuario === 'academia' && $plan !== 'academia')) {
            return back()->with('error', 'Plan no disponible para tu tipo de usuario');
        }
        
        // IDs de precios de Stripe (REEMPLAZA CON TUS IDs REALES)
        $precios = [
            'docente' => 'price_xxxxxxxxxxxxxx1', // 5€
            'academia' => 'price_xxxxxxxxxxxxxx2', // 10€
        ];
        
        $precioId = $precios[$plan] ?? null;
        
        if (!$precioId) {
            return back()->with('error', 'Plan no disponible');
        }
        
        $session = Session::create([
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
                'plan' => $plan,
                'tipo_usuario' => $tipoUsuario,
            ],
        ]);
        
        return redirect($session->url);
    }
    
    /**
     * Éxito en el pago
     */
    public function success(Request $request)
    {
        try {
            $session = Session::retrieve($request->get('session_id'));
            
            if (!$session) {
                return redirect()->route('suscripcion.planes')
                    ->with('error', 'No se pudo verificar la suscripción');
            }
            
            $user = User::find($session->metadata->user_id);
            
            if (!$user) {
                return redirect()->route('suscripcion.planes')
                    ->with('error', 'Usuario no encontrado');
            }
            
            // Crear o actualizar suscripción
            $suscripcion = Suscripcion::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'stripe_id' => $session->customer,
                    'stripe_subscription_id' => $session->subscription,
                    'plan' => $session->metadata->plan,
                    'tipo' => $session->metadata->tipo_usuario,
                    'fecha_inicio' => now(),
                    'fecha_fin' => now()->addMonth(),
                    'estado' => 'activa',
                ]
            );
            
            return redirect()->route('dashboard')
                ->with('success', '¡Suscripción activada correctamente!');
                
        } catch (\Exception $e) {
            Log::error('Error en success de suscripción: ' . $e->getMessage());
            return redirect()->route('suscripcion.planes')
                ->with('error', 'Ocurrió un error al activar la suscripción');
        }
    }
    
    /**
     * Cancelación del pago
     */
    public function cancel()
    {
        return redirect()->route('suscripcion.planes')
            ->with('info', 'Has cancelado el proceso de suscripción');
    }
    
    /**
     * Manejar webhooks de Stripe
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Webhook recibido', ['data' => $request->all()]);
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');
        
        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\Exception $e) {
            Log::error('Error verificando webhook de Stripe: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
        
        Log::info('Evento de Stripe recibido: ' . $event->type);
        
        switch ($event->type) {
            case 'customer.subscription.deleted':
                // Cancelar suscripción en tu BD
                $subscription = $event->data->object;
                Suscripcion::where('stripe_subscription_id', $subscription->id)
                    ->update(['estado' => 'cancelada', 'cancelado_en' => now()]);
                break;
                
            case 'customer.subscription.updated':
                // Actualizar suscripción
                $subscription = $event->data->object;
                $suscripcion = Suscripcion::where('stripe_subscription_id', $subscription->id)->first();
                
                if ($suscripcion) {
                    $suscripcion->update([
                        'estado' => $subscription->status,
                        'fecha_fin' => $subscription->current_period_end ? 
                            \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
                    ]);
                }
                break;
                
            case 'invoice.payment_succeeded':
                // Renovación exitosa
                $invoice = $event->data->object;
                
                if ($invoice->billing_reason === 'subscription_cycle' || 
                    $invoice->billing_reason === 'subscription_update') {
                    
                    $suscripcion = Suscripcion::where('stripe_subscription_id', $invoice->subscription)->first();
                    
                    if ($suscripcion) {
                        $suscripcion->update([
                            'estado' => 'activa',
                            'fecha_fin' => $invoice->lines->data[0]->period->end ? 
                                \Carbon\Carbon::createFromTimestamp($invoice->lines->data[0]->period->end) : 
                                now()->addMonth(),
                        ]);
                    }
                }
                break;
                
            case 'invoice.payment_failed':
                // Pago fallido
                $invoice = $event->data->object;
                
                if ($invoice->billing_reason === 'subscription_cycle') {
                    $suscripcion = Suscripcion::where('stripe_subscription_id', $invoice->subscription)->first();
                    
                    if ($suscripcion) {
                        $suscripcion->update(['estado' => 'pendiente_pago']);
                    }
                }
                break;
        }
        
        return response()->json(['received' => true], 200);
    }
    
    /**
     * Cancelar suscripción desde la app
     */
    public function cancelarSuscripcion(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->suscripcion || !$user->suscripcion->stripe_subscription_id) {
            return back()->with('error', 'No tienes una suscripción activa');
        }
        
        try {
            $subscription = \Stripe\Subscription::retrieve($user->suscripcion->stripe_subscription_id);
            $subscription->cancel();
            
            $user->suscripcion->update([
                'estado' => 'cancelada',
                'cancelado_en' => now(),
                'fecha_fin' => now(),
            ]);
            
            return back()->with('success', 'Suscripción cancelada correctamente');
            
        } catch (\Exception $e) {
            Log::error('Error cancelando suscripción: ' . $e->getMessage());
            return back()->with('error', 'Error al cancelar la suscripción');
        }
    }
    
    /**
     * Ver detalles de la suscripción actual
     */
// En SuscripcionController.php
public function detalles()
{
    $user = Auth::user();
    
    // Verificar si el usuario tiene suscripción activa
    if (!$user->tieneSuscripcionActiva()) {
        return redirect()->route('suscripcion.planes')
            ->with('info', 'No tienes una suscripción activa');
    }
    
    return view('suscripciones.detalles', [
        'user' => $user,
        'fin_suscripcion' => $user->fin_suscripcion,
        'inicio_suscripcion' => $user->inicio_suscripcion,
    ]);
}
    // Agrega este método en SuscripcionController.php

    /**
     * Procesar la solicitud de suscripción (desde el botón en planes.blade.php)
     */
    public function procesarSuscripcion(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:docente,academia',
            'precio' => 'required|numeric',
        ]);
        
        $user = Auth::user();
        $tipoUsuario = $user->rol; // Asegúrate de que este campo existe
        $planSolicitado = $request->tipo;
        
        // Validar que el tipo de usuario coincida con el plan
        if (($tipoUsuario === 'profesor' && $planSolicitado !== 'docente') || 
            ($tipoUsuario === 'academia' && $planSolicitado !== 'academia')) {
            return response()->json([
                'success' => false,
                'message' => 'Plan no disponible para tu tipo de usuario',
            ], 403);
        }
        
        // Redirigir al checkout con el plan correspondiente
        return response()->json([
            'success' => true,
            'redirect_url' => route('suscripcion.checkout', ['plan' => $planSolicitado]),
        ]);
    }    
}