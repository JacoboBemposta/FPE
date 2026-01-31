<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Donde redirigir después del login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        // Solo admin va a su panel, todos los demás a welcome
        if ($user->rol === 'admin') {
            return '/admin';
        }
        
        // Para todos los demás roles, ir a welcome
        return '/';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}