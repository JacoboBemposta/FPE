<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleLoginController extends Controller
{
    /**
     * Redirige al usuario a Google OAuth para iniciar sesión.
     */
    public function redirectToGoogle()
    {
        try {
            Log::info('Redirigiendo a Google OAuth');

            // Forzar selección de cuenta y stateless
            return Socialite::driver('google')
                ->stateless()
                ->with(['prompt' => 'select_account'])
                ->redirect();

        } catch (\Exception $e) {
            Log::error('Error en redirectToGoogle: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Error al conectar con Google');
        }
    }

    /**
     * Maneja el callback de Google OAuth.
     */
    public function handleGoogleCallback()
    {
        try {
            Log::info('Iniciando callback de Google OAuth');

            $googleUser = Socialite::driver('google')->stateless()->user();

            Log::info('Datos recibidos de Google:', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'id' => $googleUser->getId()
            ]);

            // Buscar usuario existente
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                

                // Crear usuario con Query Builder para mayor control
                $userId = DB::table('users')->insertGetId([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'password' => Hash::make(Str::random(24)),
                    'rol' => null, // Rol NULL para mostrar modal
                    'activo' => true,
                    'premium' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user = User::find($userId);
            
            } else {
              

                // Actualizar datos de Google
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                ]);
            }

            // Iniciar sesión
            Auth::login($user, true);



            // Forzar modal si no tiene rol
            if (is_null($user->rol)) {
                session(['show_role_modal' => true]);
            }

            return redirect('/');

        } catch (\Exception $e) {
            Log::error('Error en Google OAuth callback: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/login')
                ->with('error', 'Error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}