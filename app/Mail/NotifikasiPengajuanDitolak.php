<?php

namespace App\Mail;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPengajuanDitolak extends Mailable
{
    public function __construct(
        public PengajuanSurat $pengajuan,
        public string $alasan
    ) {}

    public function build()
    {
        return $this->subject('Pengajuan Surat OAP Ditolak')
            ->view('emails.pengajuan-ditolak');
    }
}
