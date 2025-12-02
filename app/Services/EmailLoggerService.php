<?php

namespace App\Services;

use App\Models\EmailEnviado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailLoggerService
{
    public function logAndSend(
        string $template,
        array $variables,
        string $recipientEmail,
        string $context,
        $mailableClass,
        ?int $senderId = null
    ): EmailEnviado {
        // Crear registro ANTES de enviar
        $emailLog = EmailEnviado::create([
            'remitente_id' => $senderId ?? auth()->id(),
            'destinatario_email' => $recipientEmail,
            'template' => $template,
            'variables' => $variables,
            'contexto' => $context,
            'status' => 'sent',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        try {
            // Enviar el correo
            Mail::to($recipientEmail)->send(new $mailableClass($variables));
            
            // Actualizar estado si el proveedor da un ID
            // $emailLog->update(['provider_message_id' => $providerId]);
            
        } catch (\Exception $e) {
            // Marcar como fallido si hay error
            $emailLog->update([
                'status' => 'failed',
                'variables' => array_merge($variables, ['error' => $e->getMessage()])
            ]);
            throw $e;
        }

        return $emailLog;
    }
}