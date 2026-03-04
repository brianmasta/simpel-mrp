<?php

namespace App\Livewire;

use App\Models\FormatSurat;
use App\Models\Marga;
use App\Models\PengajuanSurat;
use App\Models\Profil;
use App\Models\User;
use App\Models\VerifikasiPengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratOap extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public $namaLengkap, $nik, $no_kk, $asalKabupaten, $namaAyah, $namaIbu, $suku;
    public $alasan, $foto, $ktp, $kk, $akte;
    public $marga, $cekMarga;
    public $margaValid = false;
    public $pesanVerifikasi = '';
    public $riwayat = []; // <-- Tambahkan ini
    public $alasan_lain, $status_oaps, $wilayah_adat, $status_oap;
    public $sumber_marga;
    public $users = [];
    public $user_id;

    public $searchUser = '';
    public $hasilUsers = [];

    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;

    public $provinsi_id;
    public $kabupaten_id;
    public $kecamatan_id;
    public $kelurahan_id;

    public $searchProvinsi;
    public $searchKabupaten;
    public $searchKecamatan;
    public $searchKelurahan;

    public $provinsis = [];
    public $kabupatens = [];
    public $kecamatans = [];
    public $kelurahans = [];

    public $profilLengkap = false;
    public $pesanProfil;

    public function mount()
    {
        if(in_array(auth()->user()->role,['admin','petugas'])){

        $this->users = User::where('role','pengguna')
            ->whereHas('profil')
            ->with('profil')
            ->orderBy('name')
            ->get();

        }

        $profil = Profil::where('user_id', Auth::id())->with('kabupaten')->first();

        $this->profilLengkap = $this->cekProfilLengkap($profil);

        $this->pesanProfil = $this->profilLengkap
            ? null
            : '⚠️ Profil Anda belum lengkap. Lengkapi profil terlebih dahulu sebelum mengajukan surat.';

        if (!$profil) {
            abort(403, 'Profil tidak ditemukan, Silahkan Lengkapi Data Profil Anda.');
        }


        // ===== DATA PROFIL =====
        $this->nik           = $profil->nik ?? '-';
        $this->no_kk         = $profil->no_kk ?? '-';
        $this->namaLengkap   = $profil->nama_lengkap ?? '-';
        $this->namaAyah      = $profil->nama_ayah ?? '-';
        $this->namaIbu       = $profil->nama_ibu ?? '-';
        $this->asalKabupaten = $profil->kabupaten->nama ?? '-';

        // ===== VERIFIKASI MARGA =====
        $this->verifikasiMarga();

        // ===== RIWAYAT =====
        $this->riwayat = PengajuanSurat::where('user_id', Auth::id())
        ->latest()
        ->select('id','nomor_surat','alasan','status','sumber_pengajuan','created_at','file_surat','foto','ktp','kk','akte')
        ->get();

        $this->loadProfil($profil);

        $this->loadRiwayat();
    }

    public function updatedSearchUser()
    {
        if(strlen($this->searchUser) < 3){
            $this->hasilUsers = [];
            return;
        }

        $this->hasilUsers = User::where('role','pengguna')
            ->where(function($q){
                $q->where('name','like','%'.$this->searchUser.'%')
                ->orWhereHas('profil', function($q2){
                    $q2->where('nik','like','%'.$this->searchUser.'%');
                });
            })
            ->with('profil')
            ->limit(5)
            ->get();
    }

    public function pilihUser($id)
    {
        $user = User::with('profil.kabupaten')->find($id);

        if(!$user || !$user->profil){
            $this->dispatch('toast',[
                'type'=>'error',
                'message'=>'Profil pemohon belum tersedia'
            ]);
            return;
        }

        $profil = $user->profil;

        if(!$this->cekProfilLengkap($profil)){
            $this->dispatch('toast',[
                'type'=>'warning',
                'message'=>'Profil pemohon belum lengkap'
            ]);
            return;
        }

        // SET USER YANG DIPILIH
        $this->user_id = $user->id;

        // STATUS PROFIL
        $this->profilLengkap = true;

        // DATA PEMOHON
        $this->nik = $profil->nik;
        $this->no_kk = $profil->no_kk;
        $this->namaLengkap = $profil->nama_lengkap;
        $this->namaAyah = $profil->nama_ayah;
        $this->namaIbu = $profil->nama_ibu;
        $this->asalKabupaten = $profil->kabupaten->nama ?? '';

        // VERIFIKASI MARGA
        $this->verifikasiMarga();

        // RESET SEARCH RESULT
        $this->hasilUsers = [];

        // TAMPILKAN DI INPUT
        $this->searchUser = $user->name.' - '.$profil->nik;
    }

    protected function loadProfil($profil)
    {
        $this->nik           = $profil->nik ?? '-';
        $this->no_kk         = $profil->no_kk ?? '-';
        $this->namaLengkap   = $profil->nama_lengkap ?? '-';
        $this->namaAyah      = $profil->nama_ayah ?? '-';
        $this->namaIbu       = $profil->nama_ibu ?? '-';
        $this->asalKabupaten = $profil->kabupaten->nama ?? '-';

        $this->verifikasiMarga();
    }

    public function updatedUserId()
    {
        if(!$this->user_id) return;

        $profil = Profil::where('user_id',$this->user_id)
            ->with('kabupaten')
            ->first();

        if(!$profil){

            $this->dispatch('toast',[
                'type'=>'error',
                'message'=>'Profil pemohon belum tersedia'
            ]);

            $this->user_id = null;
            return;
        }

        // cek profil lengkap
        if(!$this->cekProfilLengkap($profil)){

            $this->dispatch('toast',[
                'type'=>'warning',
                'message'=>'Profil pemohon belum lengkap'
            ]);

            $this->user_id = null;
            return;
        }

        $this->loadProfil($profil);

        $this->tempat_lahir = $profil->tempat_lahir;
        $this->tanggal_lahir = $profil->tanggal_lahir;
        $this->alamat = $profil->alamat;

        $this->provinsi_id = $profil->provinsi_id;
        $this->kabupaten_id = $profil->kabupaten_id;
        $this->kecamatan_id = $profil->kecamatan_id;
        $this->kelurahan_id = $profil->kelurahan_id;
    }

    protected function loadRiwayat()
    {
        $this->riwayat = PengajuanSurat::where('user_id', Auth::id())
            ->latest()
            ->select('id','nomor_surat','alasan','status','sumber_pengajuan','created_at','file_surat','foto','ktp','kk','akte')
            ->get();
    }

    protected function cekProfilLengkap($profil)
    {
        if(!$profil){
            return false;
        }

        return
            !empty($profil->nik) &&
            !empty($profil->no_kk) &&
            !empty($profil->nama_lengkap) &&
            !empty($profil->nama_ayah) &&
            !empty($profil->nama_ibu) &&
            !empty($profil->kabupaten_id);
    }

    public function unduhSurat($id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        // 🔐 CEK OTORISASI (ADMIN / PETUGAS / PEMILIK)
        $this->authorize('view', $surat);

        if (!$surat->file_surat || !Storage::exists('html_public/' . $surat->file_surat)) {
            session()->flash('error', 'File surat tidak ditemukan.');
            return;
        }

        // Kirim response langsung sebagai download (stream)
        return response()->streamDownload(function () use ($surat) {
            echo Storage::get('html_public/' . $surat->file_surat);
        }, basename($surat->file_surat));
    }

    /* =========================================================
     |  VERIFIKASI MARGA (USER → IBU)
     ========================================================= */
    protected function cariMargaValid(?string $nama)
    {
        if (!$nama) return null;

        $nama = strtolower($nama);

        // Ambil langsung dari tabel margas
        $margas = Marga::select('marga', 'suku')->get();

        foreach ($margas as $row) {
            if (str_contains($nama, strtolower($row->marga))) {
                return [
                    'marga' => ucfirst($row->marga),
                    'suku'  => $row->sukuRelasi->nama ?? null,
                ];
            }
        }

        return null;
    }

    public function verifikasiMarga()
    {
        // 1️⃣ Coba dari nama lengkap pengguna (kata terakhir)
        $hasil = $this->ambilMargaDariNamaIbu($this->namaLengkap);

        if ($hasil) {
            $this->sumber_marga = 'user';
        } else {
            // 2️⃣ Fallback: nama lengkap ibu kandung
            $hasil = $this->ambilMargaDariNamaIbu($this->namaIbu);
            if ($hasil) {
                $this->sumber_marga = 'ibu';
            }
        }

        if (!$hasil) {
            $this->margaValid = false;
            $this->marga = null;
            $this->suku = null;
            $this->pesanVerifikasi =
                '⚠️ Marga tidak ditemukan pada nama pengguna maupun ibu kandung.';
            return;
        }

        $this->marga = $hasil['marga'];
        $this->suku  = $hasil['suku'];
        $this->margaValid = true;

        $this->pesanVerifikasi = $this->sumber_marga === 'ibu'
            ? 'ℹ️ Marga diambil dari nama ibu kandung.'
            : '✅ Marga terverifikasi dari nama pengguna.';
    }

    protected function ambilMargaDariNamaIbu(?string $namaIbu)
    {
        if (!$namaIbu) return null;

        // Normalisasi spasi
        $namaIbu = trim(preg_replace('/\s+/', ' ', $namaIbu));

        // Ambil kata terakhir (marga)
        $parts = explode(' ', $namaIbu);
        $margaIbu = strtolower(end($parts));

        // Cari marga di database
        $data = Marga::whereRaw('LOWER(marga) = ?', [$margaIbu])->first();

        if ($data) {
            return [
                'marga' => ucfirst($data->marga),
                'suku'  => $data->suku, // langsung dari tabel margas
            ];
        }

        return null;
    }

    public function kirim()
    {
        // tentukan pemohon
        $pemohonId = Auth::id();
        $sumberPengajuan = 'user';

        if(in_array(auth()->user()->role, ['admin','petugas'])){

            if(!$this->user_id){
                $this->dispatch('toast',[
                    'type'=>'error',
                    'message'=>'Pilih pemohon terlebih dahulu'
                ]);
                return;
            }
            
            $pemohonId = $this->user_id;
            $sumberPengajuan = 'petugas';


        }

        if (!$this->profilLengkap) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Pengajuan ditolak. Profil belum lengkap.'
            ]);
            return;
        }
        
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
            'foto' => 'required|image|max:2048',
            'ktp' => 'required|file|max:2048',
            'kk' => 'required|file|max:2048',
            'akte' => 'required|file|max:2048',
            
        ]);

        $alasanFinal = $this->alasan === 'Lainnya' ? $this->alasan_lain : $this->alasan;

        // ================= BATAS PENGAJUAN OAP =================

        // 1. Cek jika masih ada proses dengan alasan sama
        $cekProses = PengajuanSurat::where('user_id', $pemohonId)
            ->where('alasan', $alasanFinal)
            ->whereIn('status', ['diajukan', 'verifikasi', 'diproses'])
            ->exists();

        if ($cekProses) {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => '⚠️ Pengajuan dengan alasan ini masih diproses.'
            ]);
            return;
        }

        // ================= BATAS PENGAJUAN OAP =================

        // 2. Ambil surat terakhir yang sudah terbit
        $last = PengajuanSurat::where('user_id', $pemohonId)
            ->where('alasan', $alasanFinal)
            ->where('status', 'terbit')
            ->latest()
            ->first();

        if ($last) {
            $expired = $last->created_at->copy()->addYear();

            if (now()->lt($expired)) {
                $sisa = now()->diffInDays($expired);

                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => "❌ Surat dengan alasan ini masih berlaku. Sisa {$sisa} hari."
                ]);
                return;
            }
        }

        // ======================================================

        // Upload file
        $fotoPath = $this->foto?->store('public/surat_oap/foto');
        $ktpPath = $this->ktp?->store('public/surat_oap/ktp');
        $kkPath = $this->kk?->store('public/surat_oap/kk');
        $aktePath = $this->akte?->store('public/surat_oap/akte');

        // Simpan pengajuan
        $pengajuan = PengajuanSurat::create([
            'user_id' => $pemohonId,
            'sumber_pengajuan' => $sumberPengajuan,
            'alasan' => $alasanFinal,
            'foto' => $fotoPath,
            'ktp' => $ktpPath,
            'kk' => $kkPath,
            'akte' => $aktePath,
            'status' => 'menunggu_verifikasi',
        ]);

        // Terbitkan otomatis surat
        // $this->terbitkanSuratOap($pengajuan);
        
        // Verifikasi Pengajuan
        foreach (['foto','ktp','kk','akte'] as $dokumen) {
            VerifikasiPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'dokumen' => $dokumen,
                'status' => 'menunggu',
            ]);
        }

        // Refresh riwayat
        $this->riwayat = PengajuanSurat::where('user_id', $pemohonId)->latest()->get();

        logActivity('Mengajukan surat ' . strtoupper($alasanFinal), $pengajuan);

        $this->dispatch('pengajuanBerhasil');

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => '✅ Pengajuan berhasil dikirim. Menunggu verifikasi berkas oleh petugas.'
        ]);
        $this->reset([
            'user_id',
            'alasan',
            'alasan_lain',
            'foto',
            'ktp',
            'kk',
            'akte',
            'searchUser',
            'hasilUsers'
            ]);
    }

    protected function terbitkanSuratOap($pengajuan)
    {
        $isIpdn = str_contains(strtoupper($pengajuan->alasan), 'IPDN');

        $profil = Profil::where('user_id', $pengajuan->user_id)->with('kabupaten')->first();

        $format = FormatSurat::where('jenis', $isIpdn ? 'IPDN' : 'surat_oap')->first();

        if (!$format) {
            session()->flash('error', 'Format surat OAP belum tersedia di database.');
            return;
        }

        $tahun = now()->year;

        // $jumlahSuratTahunIni = PengajuanSurat::whereYear('created_at', $tahun)->count() + 1;
        // $nomorUrut = str_pad($jumlahSuratTahunIni, 3, '0', STR_PAD_LEFT);
        // $bulanRomawi = $this->bulanRomawi(now()->month);
        // $nomorSurat = "{$nomorUrut}/MRP-PPT/{$bulanRomawi}/{$tahun}";

        DB::transaction(function () use (&$nomorSurat, $tahun) {

            $last = PengajuanSurat::lockForUpdate()
                ->whereYear('created_at', $tahun)
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(nomor_surat,'/',1) AS UNSIGNED)) as max_no")
                ->first();

            $next = ($last->max_no ?? 0) + 1;

            // 5 digit
            $nomorUrut = str_pad($next, 5, '0', STR_PAD_LEFT);

            $nomorSurat = "{$nomorUrut}/OAP/MRP-PPT";
        });

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

        if ($isIpdn) {
            // LEGAL 216 x 356 mm
            $pdf = Pdf::loadHTML($html)
                ->setPaper('Legal', 'portrait');
        } else {
            // A4 210 x 330 mm
            $pdf = Pdf::loadHTML($html)
                ->setPaper('A4', 'portrait');
        }

        // Buat PDF preview
        // $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');
        // $this->pdfContent = base64_encode($pdf->output());

        $fileName = 'surat_oap_' . $profil->nik . '_' . time() . '.pdf';
        $relativePath = 'surat_oap/' . $fileName;

        // lama -Simpan ke storage/app/public/surat_oap/
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

    public function terbitkanJikaLengkap($pengajuanId)
    {
        $pengajuan = PengajuanSurat::findOrFail($pengajuanId);

        // 🔐 hanya admin/petugas (via policy)
        $this->authorize('view', $pengajuan);

        $belumValid = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
            ->where('status', '!=', 'valid')
            ->exists();

        if ($belumValid) {
            throw new \Exception('Masih ada berkas belum diverifikasi');
        }

        $pengajuan->update([
            'status' => 'berkas_lengkap'
        ]);

        $this->terbitkanSuratOap($pengajuan);
    }

    private function bulanRomawi($bulan)
    {
        $romawi = [1=>'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        return $romawi[$bulan] ?? '';
    }

    public function updatedFoto()
    {
        if ($this->foto) {
            $mime = $this->foto->getMimeType();

            if (!str_starts_with($mime, 'image')) {
                $this->reset('foto');

                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => 'Pas foto harus berupa gambar (JPG / PNG), bukan PDF.'
                ]);
            }
        }
    }

    public function aksesBerkas(int $pengajuanId, string $jenis): StreamedResponse
    {
        $pengajuan = PengajuanSurat::findOrFail($pengajuanId);

        // 🔐 POLICY: admin, petugas, pemilik
        $this->authorize('view', $pengajuan);

        // Mapping jenis berkas
        $map = [
            'foto'  => $pengajuan->foto,
            'ktp'   => $pengajuan->ktp,
            'kk'    => $pengajuan->kk,
            'akte'  => $pengajuan->akte,
            'surat' => $pengajuan->file_surat,
        ];

        if (!isset($map[$jenis]) || !$map[$jenis]) {
            abort(404, 'Berkas tidak ditemukan.');
        }

        $path = $map[$jenis];

        // ❗ DISK PUBLIC (SESUAI KONDISI SAAT INI)
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak tersedia.');
        }

        return response()->streamDownload(
            fn () => print(Storage::disk('public')->get($path)),
            basename($path)
        );
    }

    public function render()
    {
        return view('livewire.surat-oap')->layout('components.layouts.app');
    }
}
