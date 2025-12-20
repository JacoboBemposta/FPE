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


public function estadisticasEmails()
{
    $estadisticas = DB::table('emails_enviados')
        ->select(
            'contexto',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN enviado = 1 THEN 1 ELSE 0 END) as exitosos'),
            DB::raw('SUM(CASE WHEN enviado = 0 THEN 1 ELSE 0 END) as fallidos'),
            DB::raw('DATE(created_at) as fecha')
        )
        ->groupBy('contexto', 'fecha')
        ->orderBy('fecha', 'desc')
        ->get();

    $totales = DB::table('emails_enviados')
        ->select(
            DB::raw('COUNT(*) as total_emails'),
            DB::raw('COUNT(DISTINCT remitente_id) as remitentes_unicos'),
            DB::raw('COUNT(DISTINCT destinatario_email) as destinatarios_unicos')
        )
        ->first();

    return view('estadisticas.emails', compact('estadisticas', 'totales'));
}    




}