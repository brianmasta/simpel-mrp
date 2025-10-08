<?php

namespace App\Livewire;

use App\Models\FormatSurat;
use App\Models\Marga;
use App\Models\PengajuanMarga;
use App\Models\PengajuanSurat;
use App\Models\Profil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\RoundBlockSizeMode;

class SuratOap extends Component
{
    use WithFileUploads;

    public $namaLengkap, $nik, $asalKabupaten, $namaAyah, $namaIbu;
    public $alasan, $foto, $ktp, $kk, $akte;
    public $margaValid = false;
    public $pesanVerifikasi = '';
    public $riwayat = []; // <-- Tambahkan ini

    public function mount()
    {
        $profil = Profil::where('user_id', Auth::id())->with('kabupaten')->first();
        if ($profil) {
            $this->nik = $profil->nik;
            $this->namaLengkap = $profil->nama_lengkap;
            $this->asalKabupaten = $profil->kabupaten->nama ?? '-';
            $this->namaAyah = $profil->nama_ayah;
            $this->namaIbu = $profil->nama_ibu;
        }

        // Ambil riwayat surat pengguna
        $this->riwayat = PengajuanSurat::where('user_id', Auth::id())
            ->latest()
            ->get();

        // Verifikasi otomatis marga
        $this->verifikasiMarga();
    }

    public function unduhSurat($id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        if (!$surat->file_surat || !Storage::exists('public/' . $surat->file_surat)) {
            session()->flash('error', 'File surat tidak ditemukan.');
            return;
        }

        // Kirim response langsung sebagai download (stream)
        return response()->streamDownload(function () use ($surat) {
            echo Storage::get('public/' . $surat->file_surat);
        }, basename($surat->file_surat));
    }

    public function verifikasiMarga()
    {
        $namaLengkap = strtolower($this->namaLengkap ?? '');
        $daftarMarga = Marga::pluck('marga')->map(fn($m) => strtolower($m))->toArray();

        $margaTerdaftar = null;
        foreach ($daftarMarga as $marga) {
            if (str_contains($namaLengkap, $marga)) {
                $margaTerdaftar = $marga;
                break;
            }
        }

        if ($margaTerdaftar) {
            $this->margaValid = true;
            $this->pesanVerifikasi = "✅ Marga *{$margaTerdaftar}* terdaftar di database MRP.";
        } else {
            $this->margaValid = false;
            $this->pesanVerifikasi = "⚠️ Marga tidak ditemukan di database MRP.";
        }
    }

    public function kirim()
    {
        if (!$this->margaValid) {
            session()->flash('error', 'Marga belum terverifikasi. Pengajuan tidak dapat dilanjutkan.');
            return;
        }

        $this->validate([
            'alasan' => 'required',
            'foto' => 'nullable|image|max:2048',
            'ktp' => 'nullable|file|max:2048',
            'kk' => 'nullable|file|max:2048',
            'akte' => 'nullable|file|max:2048',
        ]);

        // Upload file
        $fotoPath = $this->foto?->store('public/surat_oap/foto');
        $ktpPath = $this->ktp?->store('public/surat_oap/ktp');
        $kkPath = $this->kk?->store('public/surat_oap/kk');
        $aktePath = $this->akte?->store('public/surat_oap/akte');

        // Simpan pengajuan
        $pengajuan = PengajuanSurat::create([
            'user_id' => Auth::id(),
            'alasan' => $this->alasan,
            'foto' => $fotoPath,
            'ktp' => $ktpPath,
            'kk' => $kkPath,
            'akte' => $aktePath,
            'status' => 'diajukan',
        ]);

        // Terbitkan otomatis surat
        $this->terbitkanSuratOap($pengajuan);

        // Refresh riwayat
        $this->riwayat = PengajuanSurat::where('user_id', Auth::id())->latest()->get();

        session()->flash('success', '✅ Surat OAP berhasil diterbitkan otomatis!');
        $this->reset(['alasan', 'foto', 'ktp', 'kk', 'akte']);
    }

    protected function terbitkanSuratOap($pengajuan)
    {
        $profil = Profil::where('user_id', Auth::id())->with('kabupaten')->first();
        $format = FormatSurat::where('jenis', 'surat_oap')->first();

        if (!$format) {
            session()->flash('error', 'Format surat OAP belum tersedia di database.');
            return;
        }

        $tahun = now()->year;
        $jumlahSuratTahunIni = PengajuanSurat::whereYear('created_at', $tahun)->count() + 1;
        $nomorUrut = str_pad($jumlahSuratTahunIni, 3, '0', STR_PAD_LEFT);
        $bulanRomawi = $this->bulanRomawi(now()->month);
        $nomorSurat = "{$nomorUrut}/MRP-PPT/{$bulanRomawi}/{$tahun}";

        // Buat kode autentikasi unik
        $kodeAutentikasi = strtoupper(substr(md5($profil->nik . now()), 0, 10));

        // Tautan verifikasi
        $verifyUrl = url("/verifikasi-surat/{$kodeAutentikasi}");

        // Generate QR Code (versi kompatibel QR-Code v5)
        $qrFileName = "qrcode_{$kodeAutentikasi}.png";
        $qrRelativePath = "qrcodes/{$qrFileName}";
        $qrAbsolutePath = storage_path("app/public/{$qrRelativePath}");

        $qr = \Endroid\QrCode\Builder\Builder::create()
            ->writer(new \Endroid\QrCode\Writer\PngWriter())
            ->data($verifyUrl)
            ->encoding(new \Endroid\QrCode\Encoding\Encoding('UTF-8'))
            ->errorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High)
            ->size(200)
            ->margin(10)
            ->roundBlockSizeMode(\Endroid\QrCode\RoundBlockSizeMode::Margin)
            ->build();

        $qr->saveToFile($qrAbsolutePath);

        // Tambahkan tag <img> untuk menampilkan QR code di surat
        $imgTag = '<div style="text-align:center; margin-top:20px;">
                        <p><strong>QR Code Validasi Surat:</strong></p>
                        <img src="' . public_path('storage/' . $qrRelativePath) . '" width="150">
                        <p>Kode Autentikasi: <strong>' . $kodeAutentikasi . '</strong></p>
                </div>';

        // Ambil QR code base64 agar bisa dimasukkan ke PDF
        // $qrImageData = base64_encode(file_get_contents($qrAbsolutePath));
        // $qrBase64 = 'data:image/png;base64,' . $qrImageData;

        // 🔧 Substitusi placeholder dinamis
        $isiSurat = str_replace(
            [
                '[[ nama_lengkap ]]',
                '[[ nik ]]',
                '[[ kabupaten ]]',
                '[[ nama_ayah ]]',
                '[[ nama_ibu ]]',
                '[[ nomorSurat ]]',
                '[[ $tanggal ]]',
                '[[ $judul ]]',
                '[[ qrcode ]]',
            ],
            [
                $profil->nama_lengkap,
                $profil->nik,
                $profil->kabupaten->nama ?? '-',
                $profil->nama_ayah,
                $profil->nama_ibu,
                $nomorSurat,
                now()->translatedFormat('d F Y'),
                $format->jenis ?? 'SURAT KETERANGAN ORANG ASLI PAPUA',
                "<img src='" . public_path('storage/'. $qrRelativePath) . "' width='100' height='100' alt='QR Code'>"
            ],
            $format->isi
        );

        // Tambahkan QR code di bawah isi surat
        $isiSurat .= $imgTag;

        $pdf = Pdf::loadHTML($isiSurat);
        $fileName = 'surat_oap_' . $profil->nik . '_' . time() . '.pdf';
        $relativePath = 'surat_oap/' . $fileName;

        // Simpan ke storage/app/public/surat_oap/
        Storage::disk('public')->put($relativePath, $pdf->output());


        // Update pengajuan yang sudah dibuat (tidak create baru)
        $pengajuan->update([
            'nomor_surat' => $nomorSurat,
            'file_surat' => 'surat_oap/' . $fileName,
            'status' => 'terbit',
            'kode_autentikasi' => $kodeAutentikasi,
            'qr_code_path' => $relativePath,
        ]);
    }

    private function bulanRomawi($bulan)
    {
        $romawi = [1=>'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        return $romawi[$bulan] ?? '';
    }

    public function render()
    {
        return view('livewire.surat-oap');
    }
}
