<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Si no hay usuario autenticado, redirigir a login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        


        // Si el usuario no tiene rol, mostrar home con modal
        if (is_null($user->rol)) {
            session(['show_role_modal' => true]);
            return view('home');
        }

        // Si tiene rol, redirigir según el rol
        switch ($user->rol) {
            case 'admin':
                return redirect()->route('admin.panel');
            case 'academia':
                return redirect()->route('academia.index');
            case 'profesor':
                return redirect()->route('profesor.miscursos');
            case 'alumno':
                return view('home')->with('info', 'Bienvenido como alumno');
            default:
                return view('home');
        }
    }
}