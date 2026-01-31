<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // public function index()
    // {
    //     // Si no hay usuario autenticado, redirigir a login
    //     if (!Auth::check()) {
    //         return redirect('/login');
    //     }

    //     $user = Auth::user();
        
    //     // Si el usuario no tiene rol, mostrar home con modal
    //     if (is_null($user->rol)) {
    //         session(['show_role_modal' => true]);
    //         return redirect('/')->with('info', 'Por favor, selecciona tu rol');
    //     }

    //     // SOLO admin va a su panel, los demás se quedan en welcome
    //     if ($user->rol === 'admin') {
    //         return redirect()->route('admin.panel');
    //     }
        
    //     // Para todos los demás roles, mostrar welcome
    //     return redirect('/')->with('success', "¡Bienvenido {$user->name}!");
    // }
}