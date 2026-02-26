<?php

namespace App\Livewire;

use App\Models\PengajuanSurat;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class VerifikasiSurat extends Component
{

    use AuthorizesRequests;

    public $kode;
    public $surat;
    public $status;

    public function mount($kode)
    {
        $this->kode = $kode;
        $this->surat = PengajuanSurat::with('profil')->where('kode_autentikasi', $kode)->first();

        $this->status = $this->surat ? 'valid' : 'invalid';
    }

    public function lihatDokumen(int $suratId): StreamedResponse
    {
        $pengajuan = PengajuanSurat::findOrFail($suratId);

        // 🔐 ATUR AKSES:
        // - admin
        // - petugas
        // - pemilik surat
        // - ATAU: publik (khusus halaman verifikasi QR)
        // ⬇️ ini versi PUBLIK TERKONTROL
        if (!request()->routeIs('verifikasi.surat')) {
            $this->authorize('view', $pengajuan);
        }

        if (!$pengajuan->file_surat) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($pengajuan->file_surat)) {
            abort(404);
        }

        return response()->streamDownload(
            fn () => print(Storage::disk('public')->get($pengajuan->file_surat)),
            basename($pengajuan->file_surat)
        );
    }

    public function render()
    {
        return view('livewire.verifikasi-surat')->layout('components.layouts.auth');
    }
}
