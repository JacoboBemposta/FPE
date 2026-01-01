<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
USE Illuminate\Support\Facades\Log; 

class UserController extends Controller
{
    // Método para actualizar SOLO el rol (usado en modal de primera vez)
    public function updateRole(Request $request)
    {
        $request->validate([
            'rol' => 'required|in:academia,profesor,alumno',
        ]);
        
        $user = Auth::user();
        $user->rol = $request->rol;
        $user->save();
        
        // NO regenerar la sesión completa - esto causa problemas con CSRF
        // Solo actualizar la sesión
        Auth::setUser($user);
        
        // Marcar en sesión que ya seleccionó rol
        Session::put('role_selected', true);
        Session::save(); // Asegurar que se guarde la sesión
        
        // Para AJAX, devolver redirección
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect_url' => $this->getRedirectUrlByRole($user->rol),
                'message' => 'Rol actualizado exitosamente'
            ]);
        }
        
        // Redirigir según el rol
        return redirect($this->getRedirectUrlByRole($user->rol));
    }
    
    // Método para actualizar perfil completo (nombre y rol)
    // Método para actualizar perfil completo (nombre y rol)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'rol' => 'required|in:academia,profesor,alumno',
        ]);
        
        $oldRole = $user->rol;
        
        // Registrar cambio para debug
        Log::info('Actualizando perfil', [
            'user_id' => $user->id,
            'old_name' => $user->name,
            'new_name' => $request->name,
            'old_role' => $oldRole,
            'new_role' => $request->rol,
        ]);
        
        $user->name = $request->name;
        $user->rol = $request->rol;
        $user->save();
        
        // Actualizar sesión
        Auth::setUser($user);
        
        $roleChanged = $oldRole !== $user->rol;
        
        // Para AJAX
        if ($request->expectsJson() || $request->ajax()) {
            $response = [
                'success' => true,
                'message' => 'Perfil actualizado correctamente',
                'role_changed' => $roleChanged,
                'new_role' => $user->rol,
                'new_name' => $user->name
            ];
            
            if ($roleChanged) {
                $response['redirect_url'] = $this->getRedirectUrlByRole($user->rol);
            }
            
            return response()->json($response);
        }
        
        // Redirigir si cambió el rol
        if ($roleChanged) {
            return redirect($this->getRedirectUrlByRole($user->rol))->with('success', 'Perfil actualizado correctamente.');
        }
        
        return back()->with('success', 'Perfil actualizado correctamente.');
    }
    
    private function getRedirectUrlByRole($role)
    {
        switch ($role) {
            case 'academia':
                return route('academia.miscursos');
            case 'profesor':
                return route('profesor.miscursos');
            case 'alumno':
                return route('alumno.index');
            case 'admin':
                return route('admin.panel');
            default:
                return url('/');
        }
    }


    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];



// public function estadisticasEmails()
// {
//     $estadisticas = DB::table('emails_enviados')
//         ->select(
//             'contexto',
//             DB::raw('COUNT(*) as total'),
//             DB::raw('SUM(CASE WHEN enviado = 1 THEN 1 ELSE 0 END) as exitosos'),
//             DB::raw('SUM(CASE WHEN enviado = 0 THEN 1 ELSE 0 END) as fallidos'),
//             DB::raw('DATE(created_at) as fecha')
//         )
//         ->groupBy('contexto', 'fecha')
//         ->orderBy('fecha', 'desc')
//         ->get();

//     $totales = DB::table('emails_enviados')
//         ->select(
//             DB::raw('COUNT(*) as total_emails'),
//             DB::raw('COUNT(DISTINCT remitente_id) as remitentes_unicos'),
//             DB::raw('COUNT(DISTINCT destinatario_email) as destinatarios_unicos')
//         )
//         ->first();

//     return view('estadisticas.emails', compact('estadisticas', 'totales'));
// }    




}