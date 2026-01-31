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
    public $attachmentFile;  // Cambiamos de path a file
    public $attachmentName;

    public function __construct($data, $attachmentFile = null, $attachmentName = null)
    {
        $this->data = $data;
        $this->attachmentFile = $attachmentFile;  // Ahora es el archivo, no la ruta
        $this->attachmentName = $attachmentName;
    }

    public function build()
    {
        $asunto = $this->data['asunto'] ?? 'Sin asunto';
        $mensaje = $this->data['mensaje'] ?? 'Sin mensaje';
        $remitente_nombre = $this->data['remitente_nombre'] ?? 'Sistema';
        $remitente_email = $this->data['remitente_email'] ?? 'sistema@example.com';

        $email = $this->view('emails.base')
                    ->subject($asunto)
                    ->with([
                        'asunto' => $asunto,
                        'mensaje' => $mensaje,
                        'remitente_nombre' => $remitente_nombre,
                        'remitente_email' => $remitente_email,
                        'fecha' => now()->format('d/m/Y H:i'),
                    ]);

        // Adjuntar archivo directamente desde el request sin guardar
        if ($this->attachmentFile && $this->attachmentFile->isValid()) {
            try {
                $email->attach(
                    $this->attachmentFile->getRealPath(),
                    [
                        'as' => $this->attachmentName ?: $this->attachmentFile->getClientOriginalName(),
                        'mime' => $this->attachmentFile->getMimeType()
                    ]
                );
                

            } catch (\Exception $e) {
                Log::error('Error adjuntando archivo directamente:', [
                    'error' => $e->getMessage(),
                    'nombre' => $this->attachmentName
                ]);
            }
        }

        return $email;
    }
}