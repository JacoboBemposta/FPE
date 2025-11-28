<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CandidaturaDocente extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $attachmentPath;

    public function __construct($data, $attachmentPath = null)
    {
        $this->data = $data;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        $email = $this->subject($this->data['subject'])
                    ->from($this->data['from_email'], $this->data['from_name'])
                    ->view('emails.candidatura-docente')
                    ->with(['data' => $this->data]);

        // Adjuntar archivo si existe
        if ($this->attachmentPath && Storage::exists($this->attachmentPath)) {
            $email->attach(Storage::path($this->attachmentPath), [
                'as' => 'CV_' . $this->data['from_name'] . '.' . pathinfo(Storage::path($this->attachmentPath), PATHINFO_EXTENSION),
                'mime' => Storage::mimeType($this->attachmentPath),
            ]);
        }

        return $email;
    }
}