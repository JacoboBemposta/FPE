<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function updateRole(Request $request)
    {
        Log::info('Iniciando updateRole', ['user_id' => Auth::id()]);

        // Validación que excluye 'admin'
        $request->validate([
            'rol' => 'required|in:academia,profesor,alumno'
        ]);

        try {
            // Obtener usuario autenticado
            $user = Auth::user();
            
            if (!$user) {
                throw new \Exception('Usuario no autenticado');
            }

            // Actualizar rol usando Eloquent
            $user->rol = $request->rol;
            $user->save();

            Log::info('Rol actualizado exitosamente', [
                'user_id' => $user->id, 
                'nuevo_rol' => $user->rol
            ]);

            // Limpiar sesión del modal
            session()->forget('show_role_modal');

            // Redirigir según el rol
            return $this->redirectByRole($request->rol)
                ->with('success', 'Rol actualizado correctamente. ¡Bienvenido a Formación Plus!');

        } catch (\Exception $e) {
            Log::error('Error en updateRole: ' . $e->getMessage());
            return redirect('/home')->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    private function redirectByRole($rol)
    {
        Log::info('Redirigiendo por rol', ['rol' => $rol]);
        
        switch ($rol) {
            case 'academia':
                return redirect()->route('academia.index');
            case 'profesor':
                return redirect()->route('profesor.miscursos');
            case 'alumno':
                return redirect('/home')->with('info', 'Bienvenido como alumno');
            default:
                return redirect('/home');
        }
    }
}