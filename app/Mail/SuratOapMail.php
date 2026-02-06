<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratOapMail extends Mailable
{
    use SerializesModels;

    public $pengajuan;
    public $filePath;

    public function __construct($pengajuan, $filePath)
    {
        $this->pengajuan = $pengajuan;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Surat Keterangan Orang Asli Papua')
            ->view('emails.surat-oap')
            ->attach($this->filePath, [
                'as' => 'Surat_OAP.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
