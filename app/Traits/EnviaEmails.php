<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\EmailBase;

trait EnviaEmails
{
public function enviarEmailRegistrado($datos)
{
    // Verificar que el usuario está autenticado
    if (!Auth::check()) {
        Log::error('Usuario no autenticado al enviar email');
        return false;
    }
    
    Log::info('Datos recibidos en EnviaEmails:', $datos);
    Log::info('Usuario autenticado:', [
        'id' => Auth::id(),
        'name' => Auth::user()->name,
        'ident' => Auth::user()->ident ?? 'No tiene ident',
        'email' => Auth::user()->email,
    ]);
    
    // Preparar variables para el log
    $remitente_nombre = Auth::user()->ident ?? Auth::user()->name;
    $remitente_email = Auth::user()->email;
    
    Log::info('Variables preparadas:', [
        'remitente_nombre' => $remitente_nombre,
        'remitente_email' => $remitente_email,
    ]);
    
    $variables = [
        'asunto' => $datos['asunto'] ?? 'Sin asunto',
        'mensaje' => $datos['mensaje'] ?? 'Sin mensaje',
        'remitente_nombre' => $remitente_nombre,
        'remitente_email' => $remitente_email,
        'remitente_tipo' => Auth::user()->rol ?? 'usuario',
        'destinatario_tipo' => $datos['destinatario_tipo'] ?? null,
        'destinatario_email' => $datos['destinatario_email'],
        'contexto' => $datos['contexto'] ?? 'generico',
        'fecha' => now()->format('d/m/Y H:i'),
    ];

    Log::info('Variables para insertar en BD:', $variables);

    try {
        // Insertar registro en la base de datos
        $id = DB::table('emails_enviados')->insertGetId([
            'remitente_id' => Auth::id(),
            'destinatario_email' => $datos['destinatario_email'],
            'template' => $datos['contexto'] ?? 'generico',
            'variables' => json_encode($variables),
            'contexto' => $datos['contexto'] ?? 'generico',
            'status' => 'sent',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent() ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Log::info('Registro insertado en BD con ID: ' . $id);

    } catch (\Exception $e) {
        Log::error('Error insertando email en BD: ' . $e->getMessage());
        return false;
    }

    try {
        Log::info('Preparando para enviar EmailBase con datos:', [
            'asunto' => $datos['asunto'] ?? 'Sin asunto',
            'mensaje' => $datos['mensaje'] ?? 'Sin mensaje',
            'contexto' => $datos['contexto'] ?? 'generico',
            'remitente_nombre' => $remitente_nombre,
            'remitente_email' => $remitente_email,
        ]);
        
        // Enviar el email
        Mail::to($datos['destinatario_email'])
            ->send(new EmailBase([
                'asunto' => $datos['asunto'] ?? 'Sin asunto',
                'mensaje' => $datos['mensaje'] ?? 'Sin mensaje',
                'contexto' => $datos['contexto'] ?? 'generico',
                'remitente_nombre' => $remitente_nombre,
                'remitente_email' => $remitente_email,
            ]));
        
        Log::info('Email enviado correctamente', [
            'para' => $datos['destinatario_email'],
            'contexto' => $datos['contexto'] ?? 'generico'
        ]);
        
        return true;

    } catch (\Exception $e) {
        // Actualizar registro si hay error
        DB::table('emails_enviados')
            ->where('id', $id)
            ->update([
                'status' => 'failed',
                'variables' => json_encode(array_merge($variables, ['error' => $e->getMessage()]))
            ]);
        
        Log::error('Error enviando email: ' . $e->getMessage());
        return false;
    }
}
}