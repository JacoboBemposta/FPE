<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show()
    {
        return view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        Log::info('Verificación iniciada', [
            'id' => $id,
            'hash' => $hash,
            'user_authenticated' => $request->user() ? 'yes' : 'no'
        ]);

        // Obtener el usuario por ID (no asumir que está autenticado)
        $user = User::find($id);

        if (!$user) {
            Log::error('Usuario no encontrado para verificación', ['id' => $id]);
            return redirect('/login')->with('error', 'Usuario no encontrado.');
        }

        // Verificar que el hash coincide con el email del usuario
        $expectedHash = sha1($user->getEmailForVerification());
        
        if (!hash_equals((string) $hash, (string) $expectedHash)) {
            Log::error('Hash de verificación no coincide', [
                'user_id' => $user->id,
                'email' => $user->email,
                'expected_hash' => $expectedHash,
                'received_hash' => $hash
            ]);
            return redirect('/login')->with('error', 'Enlace de verificación inválido.');
        }

        // Verificar si el usuario ya está verificado
        if ($user->hasVerifiedEmail()) {
            Log::info('Usuario ya verificado previamente', ['user_id' => $user->id]);
            // Autenticar al usuario y redirigir
            Auth::login($user);
            return redirect('/')->with('info', 'El email ya estaba verificado.');
        }

        // Marcar el email como verificado
        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
            Log::info('Email verificado correctamente', [
                'user_id' => $user->id,
                'email' => $user->email,
                'verified_at' => $user->email_verified_at
            ]);
        }

        // Autenticar al usuario después de verificar
        Auth::login($user);

        return redirect('/')->with('success', '¡Email verificado correctamente!');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if (!$request->user()) {
            return redirect('/login')->with('error', 'Debe iniciar sesión para reenviar el email de verificación.');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Enlace de verificación reenviado.');
    }
}