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
        
        $remitente_nombre = Auth::user()->ident ?? Auth::user()->name;
        $remitente_email = Auth::user()->email;
        
        $asuntoOriginal = $datos['asunto'] ?? 'Sin asunto';
        $asuntoConNombre = $asuntoOriginal . ' - ' . $remitente_nombre;

        // Obtener el archivo si existe
        $attachmentFile = null;
        $attachmentName = null;
        
        if (request()->hasFile('attachment') && request()->file('attachment')->isValid()) {
            $attachmentFile = request()->file('attachment');
            $attachmentName = $attachmentFile->getClientOriginalName();
            
        }

        $variables = [
            'asunto' => $asuntoConNombre,
            'mensaje' => $datos['mensaje'] ?? 'Sin mensaje',
            'remitente_nombre' => $remitente_nombre,
            'remitente_email' => $remitente_email,
            'remitente_tipo' => Auth::user()->rol ?? 'usuario',
            'destinatario_tipo' => $datos['destinatario_tipo'] ?? null,
            'destinatario_email' => $datos['destinatario_email'],
            'fecha' => now()->format('d/m/Y H:i'),
            'curso_id' => $datos['curso_id'] ?? null,
            'tiene_adjunto' => !is_null($attachmentFile),
            'attachment_name' => $attachmentName,
        ];

        try {
            // Insertar registro en la base de datos
            $id = DB::table('emails_enviados')->insertGetId([
                'remitente_id' => Auth::id(),
                'curso_id' => $datos['curso_id'] ?? null,
                'destinatario_email' => $datos['destinatario_email'],
                'contexto' => $datos['contexto'] ?? 'generico',
                'status' => 'sent',
                'created_at' => now(),
            ]);
            

        } catch (\Exception $e) {
            Log::error('Error insertando email en BD: ' . $e->getMessage());
            return false;
        }

        try {
            // ========== ENVÍO DEL EMAIL CON ADJUNTO ==========
            $mailData = [
                'asunto' => $asuntoConNombre,
                'mensaje' => $datos['mensaje'] ?? 'Sin mensaje',
                'remitente_nombre' => $remitente_nombre,
                'remitente_email' => $remitente_email,
            ];

                // Enviar usando mail() de PHP
            $to = $datos['destinatario_email'];
            $subject = $mailData['asunto'];
            $message = $mailData['mensaje'];
            $headers = [
                'From: info@redfpe.es',
                'Reply-To: info@redfpe.es',
                'X-Mailer: PHP/' . phpversion(),
                'Content-Type: text/plain; charset=utf-8'
            ];
                    
             $result = mail($to, $subject, $message, implode("\r\n", $headers));


            return true;

        } catch (\Exception $e) {
            // Actualizar registro si hay error
            DB::table('emails_enviados')
                ->where('id', $id)
                ->update([
                    'status' => 'failed',
                ]);
            
            Log::error('Error enviando email: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}