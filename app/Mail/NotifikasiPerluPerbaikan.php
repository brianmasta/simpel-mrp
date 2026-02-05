<?php

namespace App\Mail;

use App\Models\PengajuanSurat;
use App\Models\VerifikasiPengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPerluPerbaikan extends Mailable
{
    use Queueable, SerializesModels;

    public PengajuanSurat $pengajuan;
    public $catatan;
    public string $perbaikanUrl;

    public function __construct(PengajuanSurat $pengajuan)
    {
        $this->pengajuan = $pengajuan;

                // ambil catatan berkas yang perlu perbaikan
        $this->catatan = VerifikasiPengajuan::where('pengajuan_id', $pengajuan->id)
            ->where('status', 'perlu_perbaikan')
            ->whereNotNull('catatan')
            ->get();

        // link langsung ke halaman perbaikan
        $this->perbaikanUrl = url('/perbaikan-berkas/' . $pengajuan->id);
    }

    public function build()
    {
        return $this->subject('⚠️ Perbaikan Berkas Surat OAP')
            ->view('emails.perlu-perbaikan');
    }
}
