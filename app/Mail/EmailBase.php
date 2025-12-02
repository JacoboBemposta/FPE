<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailBase extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        Log::info('EmailBase constructor - Datos recibidos:', $data);
        $this->data = $data;
    }

    public function build()
    {
        Log::info('EmailBase build() - Preparando vista con datos:', $this->data);
        
        // Asegúrate de que las variables existen
        $asunto = $this->data['asunto'] ?? 'Sin asunto';
        $mensaje = $this->data['mensaje'] ?? 'Sin mensaje';
        $remitente_nombre = $this->data['remitente_nombre'] ?? 'Sistema';
        $remitente_email = $this->data['remitente_email'] ?? 'sistema@example.com';
        $contexto = $this->data['contexto'] ?? 'generico';
        
        Log::info('EmailBase build() - Variables finales:', [
            'asunto' => $asunto,
            'mensaje' => $mensaje,
            'remitente_nombre' => $remitente_nombre,
            'remitente_email' => $remitente_email,
            'contexto' => $contexto,
        ]);
        
        return $this->view('emails.base')
                    ->subject($asunto)
                    ->with([
                        'asunto' => $asunto,
                        'mensaje' => $mensaje,
                        'remitente_nombre' => $remitente_nombre,
                        'remitente_email' => $remitente_email,
                        'contexto' => $contexto,
                        'fecha' => now()->format('d/m/Y H:i'),
                    ]);
    }
}