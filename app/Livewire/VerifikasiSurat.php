<?php

namespace App\Livewire;

use App\Models\PengajuanSurat;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VerifikasiSurat extends Component
{

    public $kode;
    public $surat;
    public $status;

    public $inputPassword;
    public $showPasswordModal = false;
    public $selectedSuratId;

    public $inputOtp;

    public function mount($kode)
    {
        $this->kode = $kode;
        $this->surat = PengajuanSurat::with('profil')->where('kode_autentikasi', $kode)->first();

        $this->status = $this->surat ? 'valid' : 'invalid';
    }

    public function lihatDokumen(int $suratId): StreamedResponse
    {
        $pengajuan = PengajuanSurat::findOrFail($suratId);

        // 🔐 CEK AKSES OTP (WAJIB)
        $sessionKey = 'akses_surat_' . $pengajuan->id;

        if (!session()->has($sessionKey)) {
            abort(403, 'Akses ditolak. Silakan verifikasi OTP terlebih dahulu.');
        }

        // 🔐 OPTIONAL: cek expired (kalau pakai expiry)
        $expired = session($sessionKey);

        if ($expired instanceof \Carbon\Carbon && now()->gt($expired)) {
            session()->forget($sessionKey);
            abort(403, 'Akses sudah kadaluarsa.');
        }

        // 🔐 hanya boleh kalau sudah terbit
        if ($pengajuan->status !== 'terbit') {
            abort(403, 'Surat belum tersedia.');
        }

        if (!$pengajuan->file_surat) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($pengajuan->file_surat)) {
            abort(404);
        }

        // 🔥 HAPUS SESSION (OTP sekali pakai)
        session()->forget($sessionKey);

        return response()->streamDownload(
            fn () => print(Storage::disk('public')->get($pengajuan->file_surat)),
            basename($pengajuan->file_surat)
        );
    }

    public function kirimOtp($id)
    {
        $pengajuan = PengajuanSurat::with('user', 'profil')->findOrFail($id);

        if (!$pengajuan->user || !$pengajuan->user->email) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Email tidak tersedia'
            ]);
            return;
        }

        if (!$pengajuan->profil || !$pengajuan->profil->no_hp) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Nomor HP tidak tersedia'
            ]);
            return;
        }

        $lastOtp = $pengajuan->otp_expired_at;

        if ($lastOtp && now()->lt($lastOtp->subMinutes(4))) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'Tunggu sebentar sebelum minta OTP lagi'
            ]);
            return;
        }

        // 🔢 generate PIN
        $otp = rand(100000, 999999);

        // simpan (hash biar aman)
        $pengajuan->update([
            'otp' => bcrypt($otp),
            'otp_expired_at' => now()->addMinutes(5)
        ]);

        // =========================
        // 📧 KIRIM EMAIL
        // =========================
        Mail::raw("Kode OTP Anda: $otp (berlaku 5 menit)", function ($message) use ($pengajuan) {
            $message->to($pengajuan->user->email)
                    ->subject('OTP Download Surat OAP');
        });

        $noHp = preg_replace('/^0/', '62', $pengajuan->profil->no_hp);

        // =========================
        // 📱 KIRIM WHATSAPP (Fonnte)
        // =========================
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post('https://api.fonnte.com/send', [
            'target' => $noHp,
            'message' => "Kode OTP Anda: $otp\nBerlaku 5 menit.\nJangan berikan ke siapapun."
        ]);

        // tampilkan modal input OTP
        $this->selectedSuratId = $id;
        $this->showPasswordModal = true;

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'OTP dikirim ke Email & WhatsApp (berlaku 5 menit)'
        ]);
    }


    public function verifikasiOtp()
    {

        $pengajuan = PengajuanSurat::findOrFail($this->selectedSuratId);

        $key = 'otp_attempt_' . $pengajuan->id;

        if (!session()->has($key)) {
            session()->put($key, 0);
        }

        if (session($key) >= 3) {
            abort(429, 'Terlalu banyak percobaan OTP.');
        }

        if (!$pengajuan->otp_expired_at || now()->gt($pengajuan->otp_expired_at)) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'OTP sudah kadaluarsa!'
            ]);
            return;
        }

        if (!Hash::check($this->inputOtp, $pengajuan->otp)) {

            session()->increment($key);

            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'OTP salah!'
            ]);
            return;
        }

        session()->forget($key);

        $pengajuan->update([
            'otp' => null,
            'otp_expired_at' => null
        ]);

        $this->inputOtp = '';
        $this->showPasswordModal = false;

        logActivity(
            'Verifikasi OTP & download surat',
            $pengajuan
        );

        $url = URL::temporarySignedRoute(
            'berkas.akses',
            now()->addMinutes(5),
            [
                'pengajuan' => $pengajuan->id,
                'jenis' => 'surat'
            ]
        );

        logger('OTP VERIFIED');

        $this->dispatch('download-file', url: $url);
    }

    public function render()
    {
        return view('livewire.verifikasi-surat')->layout('components.layouts.auth');
    }
}
