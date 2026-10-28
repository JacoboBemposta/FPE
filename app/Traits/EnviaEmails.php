<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait EnviaEmails
{
    public function enviarEmailRegistrado($datos)
    {
        $remitente_nombre = Auth::user()->ident ?? Auth::user()->name;
        $remitente_email = Auth::user()->email;

        $asuntoOriginal = $datos['asunto'] ?? 'Sin asunto';
        $asuntoConNombre = $asuntoOriginal . ' - ' . $remitente_nombre;

        // Obtener el archivo adjunto si existe
        $attachmentFile = null;
        $attachmentName = null;

        if (request()->hasFile('attachment') && request()->file('attachment')->isValid()) {
            $attachmentFile = request()->file('attachment');
            $attachmentName = $attachmentFile->getClientOriginalName();
        }

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
            $to = $datos['destinatario_email'];
            $subject = '=?UTF-8?B?' . base64_encode($asuntoConNombre) . '?=';
            $plainMessage = $datos['mensaje'] ?? 'Sin mensaje';
            $replyTo = filter_var($remitente_email, FILTER_VALIDATE_EMAIL) ? $remitente_email : 'info@redfpe.es';

            if ($attachmentFile && is_readable($attachmentFile->getRealPath())) {
                $boundary = '=_NextPart_' . md5((string) microtime(true));
                $mimeType = $attachmentFile->getMimeType() ?: 'application/octet-stream';
                $fileContent = chunk_split(base64_encode(file_get_contents($attachmentFile->getRealPath())));
                $safeFilename = addslashes(basename($attachmentName));

                $headers = [
                    'From: RedFPE <info@redfpe.es>',
                    'Reply-To: ' . $replyTo,
                    'MIME-Version: 1.0',
                    'X-Mailer: PHP/' . phpversion(),
                    'Content-Type: multipart/mixed; boundary="' . $boundary . '"',
                ];

                $body = "--{$boundary}\r\n"
                    . "Content-Type: text/plain; charset=utf-8\r\n"
                    . "Content-Transfer-Encoding: 8bit\r\n\r\n"
                    . $plainMessage . "\r\n\r\n"
                    . "--{$boundary}\r\n"
                    . "Content-Type: {$mimeType}; name=\"{$safeFilename}\"\r\n"
                    . "Content-Disposition: attachment; filename=\"{$safeFilename}\"\r\n"
                    . "Content-Transfer-Encoding: base64\r\n\r\n"
                    . $fileContent . "\r\n"
                    . "--{$boundary}--";

                $result = @mail($to, $subject, $body, implode("\r\n", $headers));
            } else {
                $headers = [
                    'From: RedFPE <info@redfpe.es>',
                    'Reply-To: ' . $replyTo,
                    'MIME-Version: 1.0',
                    'X-Mailer: PHP/' . phpversion(),
                    'Content-Type: text/plain; charset=utf-8',
                ];

                $result = @mail($to, $subject, $plainMessage, implode("\r\n", $headers));
            }

            if ($result === false) {
                throw new \RuntimeException('La función mail() devolvió false.');
            }

            return true;
        } catch (\Exception $e) {
            DB::table('emails_enviados')
                ->where('id', $id)
                ->update([
                    'status' => 'failed',
                ]);

            Log::error('Error enviando email: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
