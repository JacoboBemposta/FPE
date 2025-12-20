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

        $this->data = $data;
    }

    public function build()
    {


        $asunto = $this->data['asunto'] ?? 'Sin asunto';
        $mensaje = $this->data['mensaje'] ?? 'Sin mensaje';
        $remitente_nombre = $this->data['remitente_nombre'] ?? 'Sistema';
        $remitente_email = $this->data['remitente_email'] ?? 'sistema@example.com';

        

        
        return $this->view('emails.base')
                    ->subject($asunto)
                    ->with([
                        'asunto' => $asunto,
                        'mensaje' => $mensaje,
                        'remitente_nombre' => $remitente_nombre,
                        'remitente_email' => $remitente_email,
                        'fecha' => now()->format('d/m/Y H:i'),
                    ]);
    }
}