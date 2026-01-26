<?php

namespace App\Livewire;

use App\Models\FormatSurat;
use App\Models\Marga;
use App\Models\PengajuanSurat;
use App\Models\Profil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class SuratOap extends Component
{
    use WithFileUploads;

    public $namaLengkap, $nik, $no_kk, $asalKabupaten, $namaAyah, $namaIbu, $suku;
    public $alasan, $foto, $ktp, $kk, $akte;
    public $marga, $cekMarga;
    public $margaValid = false;
    public $pesanVerifikasi = '';
    public $riwayat = []; // <-- Tambahkan ini
    public $alasan_lain, $status_oaps, $wilayah_adat, $status_oap;

    public function mount()
    {
        $profil = Profil::where('user_id', Auth::id())->with('kabupaten')->first();

        // Ambil kata terakhir sebagai marga
        $parts = explode(' ', trim($profil?->nama_lengkap));
        $this->marga = ucfirst(strtolower(end($parts)));
        $cekMarga = Marga::where('marga', $this->marga)->first();

        // Cek apakah marga & suku valid
        if ($cekMarga && $cekMarga->suku) {
            $this->suku = $cekMarga->suku;
            $this->margaValid = true;
            $this->pesanVerifikasi = "✅ Marga '{$this->marga}' dengan suku '{$this->suku}' terverifikasi.";
        } else {
            $this->suku = null;
            $this->margaValid = false;
            $this->pesanVerifikasi = "⚠️ Marga '{$this->marga}' tidak ditemukan atau belum memiliki suku. Pengajuan tidak dapat dilakukan.";
        }
        
        // dd($cekMarga->suku);

        if ($profil) {
            $this->nik = $profil->nik;
            $this->no_kk = $profil->no_kk;
            $this->namaLengkap = $profil->nama_lengkap;
            $this->asalKabupaten = $profil->kabupaten->nama ?? '-';
            $this->namaAyah = $profil->nama_ayah;
            $this->namaIbu = $profil->nama_ibu;
            $this->suku = $cekMarga?->suku;
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

        if (!$surat->file_surat || !Storage::exists('html_public/' . $surat->file_surat)) {
            session()->flash('error', 'File surat tidak ditemukan.');
            return;
        }

        // Kirim response langsung sebagai download (stream)
        return response()->streamDownload(function () use ($surat) {
            echo Storage::get('html_public/' . $surat->file_surat);
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

        // CEK WAJIB FILE
        if (!$this->foto || !$this->ktp || !$this->kk || !$this->akte) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => '⚠️ Semua berkas wajib diunggah'
            ]);
            return;
        }

        $this->validate([
            'alasan' => 'required',
            'alasan_lain' => $this->alasan === 'Lainnya' ? 'required|string|max:255' : 'nullable',
            'foto' => 'nullable|image|max:2048',
            'ktp' => 'nullable|file|max:2048',
            'kk' => 'nullable|file|max:2048',
            'akte' => 'nullable|file|max:2048',
            
        ]);

        $alasanFinal = $this->alasan === 'Lainnya' ? $this->alasan_lain : $this->alasan;

        // Upload file
        $fotoPath = $this->foto?->store('public/surat_oap/foto');
        $ktpPath = $this->ktp?->store('public/surat_oap/ktp');
        $kkPath = $this->kk?->store('public/surat_oap/kk');
        $aktePath = $this->akte?->store('public/surat_oap/akte');

        // Simpan pengajuan
        $pengajuan = PengajuanSurat::create([
            'user_id' => Auth::id(),
            'alasan' => $alasanFinal,
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

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => '✅ Surat OAP berhasil diterbitkan otomatis!'
        ]);
        $this->dispatch('scrollToTop'); // <-- tambah ini
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
        
        $qrDirectory = public_path('qrcodes');
        
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }
        
        $qrAbsolutePath = $qrDirectory . '/' . $qrFileName;

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

            // === AMBIL FOTO DARI FOLDER PRIVATE ===
        $fotoFullPath = storage_path('app/private/' . $pengajuan->foto);


        if (!file_exists($fotoFullPath)) {
            $fotoBase64 = ''; // fallback jika tidak ada foto
        } else {
            $fotoData = file_get_contents($fotoFullPath);
            $fotoBase64 = 'data:image/jpeg;base64,' . base64_encode($fotoData);
        }

        // === KONVERSI QR CODE JADI BASE64 UNTUK DOMPDF ===
        $qrData = file_get_contents($qrAbsolutePath);
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrData);

        // Ambil kata terakhir sebagai marga
        $parts = explode(' ', trim($profil->nama_lengkap));
        $this->marga = ucfirst(strtolower(end($parts)));
        $cekMarga = Marga::where('marga', $this->marga)->first();
        // dd($cekMarga->suku);

        $data = [
            'nama_lengkap' => $profil->nama_lengkap ?? 'Nama Pengguna',
            'nik' => $profil->nik ?? '1234567890',
            'nik_kk' => $profil->no_kk ?? '1234567890',
            'nomor_surat' => $nomorSurat,
            'kabupaten' => $profil->kabupaten->nama ?? '',
            'nama_ayah' => $profil->nama_ayah ?? '',
            'nama_ibu' => $profil->nama_ibu ?? '',
            'alasan' => $pengajuan->alasan,
            'suku' => $cekMarga->suku,
            'foto' => $fotoBase64,
            'qrcode' => $qrBase64,
            'tanggal' => now()->translatedFormat('d F Y'),
        ];

        // dd($data);

        // Ganti placeholder dinamis di template
        $html = $format->isi_html;
        foreach ($data as $key => $value) {
            $html = str_replace('[[' . $key . ']]', $value, $html);
        }

        // Buat PDF preview
        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');
        // $this->pdfContent = base64_encode($pdf->output());

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
        return view('livewire.surat-oap')->layout('layouts.app');
    }
}
