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
    public function redirectToGoogle()
    {
        try {
            Log::info('Redirigiendo a Google OAuth');
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Error en redirectToGoogle: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Error al conectar con Google');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Iniciando callback de Google OAuth');

            $googleUser = Socialite::driver('google')->user();

            Log::info('Datos recibidos de Google:', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'id' => $googleUser->getId()
            ]);

            // Buscar usuario existente
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                Log::info('Creando nuevo usuario desde Google: ' . $googleUser->getEmail());
                
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
                Log::info('Nuevo usuario creado con ID: ' . $userId);
            } else {
                Log::info('Usuario existente encontrado: ' . $user->id);
                
                // Actualizar datos de Google
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                ]);
            }

            // Iniciar sesión
            Auth::login($user, true);
            
            Log::info('Login Google exitoso', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'rol' => $user->rol
            ]);

            // Forzar modal si no tiene rol
            if (is_null($user->rol)) {
                session(['show_role_modal' => true]);
                Log::info('Usuario sin rol - Modal activado');
            }

            return redirect('/home');

        } catch (\Exception $e) {
            Log::error('Error en Google OAuth callback: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/login')
                   ->with('error', 'Error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}