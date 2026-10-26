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
     * Obtiene la URI de redirección adaptada al entorno actual (localhost o producción).
     */
    protected function getRedirectUri(): string
    {
        $host = request()->getHost();
        if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
            return request()->getSchemeAndHttpHost() . '/auth/google/callback';
        }

        return config('services.google.redirect') ?: url('/auth/google/callback');
    }

    /**
     * Configura el driver de Socialite adaptando la URI y SSL en entorno local (XAMPP Windows).
     */
    protected function googleDriver()
    {
        $redirectUri = $this->getRedirectUri();
        $driver = Socialite::driver('google')
            ->stateless()
            ->redirectUrl($redirectUri);

        if (in_array(request()->getHost(), ['127.0.0.1', 'localhost'], true)) {
            $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }

        return $driver;
    }

    /**
     * Redirige al usuario a Google OAuth para iniciar sesión.
     */
    public function redirectToGoogle()
    {
        try {
            return $this->googleDriver()
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
            $googleUser = $this->googleDriver()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Crear nuevo usuario
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

            // Limpiar sesión previa
            session()->forget('role_selected');

            // Redirigir al home donde se mostrará el modal si no tiene rol
            return redirect('/');

        } catch (\Exception $e) {
            Log::error('Error en Google OAuth callback: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Error al iniciar sesión con Google.');
        }
    }
}
