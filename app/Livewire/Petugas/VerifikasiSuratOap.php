<?php

namespace App\Livewire\Petugas;

use App\Mail\NotifikasiPengajuanDitolak;
use App\Mail\SuratOapMail;
use App\Models\FormatSurat;
use App\Models\Marga;
use App\Models\PengajuanSurat;
use App\Models\Profil;
use App\Models\VerifikasiPengajuan;
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\High;
use Endroid\QrCode\Label\Margin\Margin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class VerifikasiSuratOap extends Component
{
    public $selectedPengajuan;
    public $verifikasi = [];

    public $alasanPenolakan = '';
    public $showTolakModal = false;

    /* ===================== PILIH PENGAJUAN ===================== */
    public function pilihPengajuan($id)
    {
        $this->selectedPengajuan = PengajuanSurat::with(['user', 'verifikasi'])
            ->findOrFail($id);

        foreach ($this->selectedPengajuan->verifikasi as $v) {
            $this->verifikasi[$v->id] = [
                'status' => $v->status,
                'catatan' => $v->catatan,
            ];
        }
    }

    /* ===================== SIMPAN VERIFIKASI ===================== */
    public function simpanVerifikasi()
    {
        DB::transaction(function () {

            foreach ($this->verifikasi as $id => $data) {
                VerifikasiPengajuan::where('id', $id)->update([
                    'status' => $data['status'],
                    'catatan' => $data['catatan'],
                ]);
            }

            $pengajuanId = $this->selectedPengajuan->id;

            $total = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)->count();
            $valid = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
                ->where('status', 'valid')->count();

            $perluPerbaikan = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
                ->where('status', 'perlu_perbaikan')->exists();

            $menunggu = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
                ->where('status', 'menunggu')->exists();

            if ($perluPerbaikan) {
                $this->selectedPengajuan->update(['status' => 'perlu_perbaikan']);
            } elseif ($valid === $total) {
                $this->selectedPengajuan->update(['status' => 'berkas_lengkap']);
            } elseif ($menunggu) {
                $this->selectedPengajuan->update(['status' => 'menunggu_verifikasi']);
            }
        });

        $this->selectedPengajuan->refresh();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Verifikasi berkas berhasil disimpan'
        ]);
    }

    /* ===================== BUAT SURAT (DRAFT / PREVIEW) ===================== */
    public function buatSuratPreview($pengajuanId)
    {
        $pengajuan = PengajuanSurat::findOrFail($pengajuanId);

        $belumValid = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
            ->where('status', '!=', 'valid')
            ->exists();

        if ($belumValid) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Masih ada berkas belum valid'
            ]);
            return;
        }

        // 👉 PAKAI METHOD LAMA KAMU (MODE DRAFT)
        $path = $this->terbitkanSuratOap($pengajuan, 'draft');

        $pengajuan->update([
            'status' => 'draft_surat',
            'file_surat' => $path,
        ]);

        $this->selectedPengajuan = $pengajuan->fresh();

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Surat berhasil dibuat (DRAFT). Silakan preview.'
        ]);
    }

    /* ===================== TERBITKAN SURAT (FINAL) ===================== */
    public function terbitkanSurat()
    {
        if ($this->selectedPengajuan->status !== 'draft_surat') {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Surat belum siap diterbitkan'
            ]);
            return;
        }

        DB::transaction(function () {
            // 👉 GENERATE FINAL (NOMOR + QR + PDF FINAL)
            $path = $this->terbitkanSuratOap($this->selectedPengajuan, 'final');

            $this->selectedPengajuan->update([
                'status' => 'terbit',
                'file_surat' => $path,
            ]);
        });

        $this->selectedPengajuan->refresh();

            /* =====================================================
            |  🔔 NOTIFIKASI SURAT TERBIT
            ===================================================== */

            // 🔗 LINK UNDUH & VERIFIKASI
            // $linkUnduh = route('view.private', [
            //     'path' => $this->selectedPengajuan->file_surat
            // ]);

            $linkUnduh = url(
                Storage::url($this->selectedPengajuan->file_surat)
            );

            $linkVerifikasi = url(
                '/verifikasi-surat/' . $this->selectedPengajuan->kode_autentikasi
            );

            // =========================
            // 📲 WHATSAPP (FONNTE)
            // =========================
            if ($this->selectedPengajuan->user?->profil?->no_hp) {
                FonnteService::send(
                    $this->selectedPengajuan->user->profil->no_hp,
                    "✅ *SIMPEL-MRP*\n\n" .
                    "Yth. Bapak/Ibu,\n\n" .
                    "Kami informasikan bahwa pengajuan\n" .
                    "*Surat Keterangan Orang Asli Papua (OAP)* Anda\n" .
                    "telah *RESMI DITERBITKAN*.\n\n" .
                    "📄 *Nomor Surat:*\n" .
                    "{$this->selectedPengajuan->nomor_surat}\n\n" .
                    "⬇️ *Unduh Surat:*\n" .
                    "🔗 {$linkUnduh}\n\n" .
                    "🔍 *Verifikasi Keaslian Surat:*\n" .
                    "🔗 {$linkVerifikasi}\n\n" .
                    "Terima kasih telah menggunakan layanan\n" .
                    "*SIMPEL-MRP Papua Tengah*."
                );
            }

                    // ✅ path file sesuai Storage::url()
            $path = storage_path(
                'app/public/' . $this->selectedPengajuan->file_surat
            );

            // =========================
            // 📧 EMAIL
            // =========================
            if ($this->selectedPengajuan->user?->email) {
                Mail::to($this->selectedPengajuan->user->email)
                    ->send(new SuratOapMail($this->selectedPengajuan, $path));
            }

            /* ===================================================== */

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => '🎉 Surat resmi diterbitkan & notifikasi terkirim'
            ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => '🎉 Surat resmi diterbitkan'
        ]);
    }

    protected function terbitkanSuratOap(PengajuanSurat $pengajuan, string $mode = 'final')
    {
    $isDraft = $mode === 'draft';
    $isIpdn  = str_contains(strtoupper($pengajuan->alasan), 'IPDN');

    /* =====================================================
     |  PROFIL PEMOHON
     ===================================================== */
    $profil = Profil::where('user_id', $pengajuan->user_id)
        ->with('kabupaten')
        ->firstOrFail();

    /* =====================================================
     |  FORMAT SURAT (DB)
     ===================================================== */
    $format = FormatSurat::where('jenis', $isIpdn ? 'IPDN' : 'surat_oap')->first();

    if (!$format) {
        throw new \Exception('Format surat OAP belum tersedia di database.');
    }

    /* =====================================================
     |  NOMOR SURAT
     ===================================================== */
    if ($isDraft) {
        $nomorSurat = 'DRAFT-' . now()->format('Ymd-His');
        $kodeAutentikasi = null;
        $qrBase64 = '';
    } else {
        $tahun = now()->year;

        DB::transaction(function () use (&$nomorSurat, $tahun) {
            $last = PengajuanSurat::lockForUpdate()
                ->whereYear('created_at', $tahun)
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(nomor_surat,'/',1) AS UNSIGNED)) as max_no")
                ->first();

            $next = ($last->max_no ?? 0) + 1;
            $nomorUrut = str_pad($next, 5, '0', STR_PAD_LEFT);
            $nomorSurat = "{$nomorUrut}/OAP/MRP-PPT";
        });

        /* ================= QR + AUTENTIKASI ================= */
        $kodeAutentikasi = strtoupper(substr(md5($profil->nik . now()), 0, 10));
        $verifyUrl = url("/verifikasi-surat/{$kodeAutentikasi}");

        $qrDirectory = storage_path('app/private/qrcodes');
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }

        $qrAbsolutePath = $qrDirectory . "/qrcode_{$kodeAutentikasi}.png";

        Builder::create()
            ->writer(new PngWriter())
            ->data($verifyUrl)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::High)
            ->size(200)
            ->margin(10)
            ->roundBlockSizeMode(\Endroid\QrCode\RoundBlockSizeMode::Margin)
            ->build()
            ->saveToFile($qrAbsolutePath);

        $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrAbsolutePath));
    }

    /* =====================================================
     |  FOTO (PRIVATE → BASE64)
     ===================================================== */
        $fotoFullPath = storage_path('app/private/' . $pengajuan->foto);


        if (!file_exists($fotoFullPath)) {
            $fotoBase64 = ''; // fallback jika tidak ada foto
        } else {
            $fotoData = file_get_contents($fotoFullPath);
            $fotoBase64 = 'data:image/jpeg;base64,' . base64_encode($fotoData);
        }

    /* =====================================================
     |  MARGA & SUKU
     ===================================================== */
    // $parts = explode(' ', trim($profil->marga_terverifikasi));
    // $marga = ucfirst(strtolower(end($parts)));
    $cekMarga = Marga::where('marga', $profil->marga_terverifikasi)->first();
    $suku = $cekMarga->suku ?? '-';

    /* =====================================================
     |  DATA UNTUK TEMPLATE
     ===================================================== */
    $data = [
        'nama_lengkap' => $profil->nama_lengkap,
        'nik'          => $profil->nik,
        'nik_kk'       => $profil->no_kk,
        'nomor_surat'  => $nomorSurat,
        'kabupaten'    => $profil->kabupaten->nama ?? '',
        'nama_ayah'    => $profil->nama_ayah,
        'nama_ibu'     => $profil->nama_ibu,
        'alasan'       => $pengajuan->alasan,
        'suku'         => $suku,
        'foto'         => $fotoBase64,
        'qrcode'       => $qrBase64,
        'tanggal'      => now()->translatedFormat('d F Y'),
    ];

    /* =====================================================
     |  INJECT KE TEMPLATE
     ===================================================== */
    $html = $format->isi_html;
    foreach ($data as $key => $value) {
        $html = str_replace('[[' . $key . ']]', $value, $html);
    }

    if ($isDraft) {
        $html = str_replace(
            '</body>',
            '<div style="position:fixed;top:40%;left:20%;
             font-size:80px;color:#ccc;
             transform:rotate(-30deg);opacity:0.3;">
             DRAFT
             </div></body>',
            $html
        );
    }

    /* =====================================================
     |  PDF
     ===================================================== */
    $pdf = Pdf::loadHTML($html)
        ->setPaper($isIpdn ? 'Legal' : 'A4', 'portrait');

    $folder = $isDraft
        ? 'private/surat_oap/draft'
        : 'private/surat_oap/final';

    $filename = 'surat_oap_' . $profil->nik . '_' . time() . '.pdf';
    $path = $folder . '/' . $filename;

    Storage::disk('public')->put($path, $pdf->output());

    /* =====================================================
     |  UPDATE PENGAJUAN (FINAL SAJA)
     ===================================================== */
    if (!$isDraft) {
        $pengajuan->update([
            'nomor_surat'       => $nomorSurat,
            'kode_autentikasi'  => $kodeAutentikasi,
            'qr_code_path'      => $path,
        ]);
    }

    return $path;
    }

    public function bukaModalTolak()
    {
        if (!$this->selectedPengajuan) {
            return;
        }

        if ($this->selectedPengajuan->status === 'terbit') {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Surat sudah terbit dan tidak dapat ditolak'
            ]);
            return;
        }

        $this->alasanPenolakan = '';
        $this->showTolakModal = true;
    }

    public function tolakPengajuan()
    {
        $this->validate([
            'alasanPenolakan' => 'required|string|min:10',
        ], [
            'alasanPenolakan.required' => 'Alasan penolakan wajib diisi',
        ]);

        if (!$this->selectedPengajuan) {
            return;
        }

        if ($this->selectedPengajuan->status === 'terbit') {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Pengajuan tidak dapat ditolak karena sudah terbit'
            ]);
            return;
        }

        DB::transaction(function () {
            $this->selectedPengajuan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $this->alasanPenolakan,
            ]);
        });

        $this->selectedPengajuan->refresh();

        /* =========================
        |  🔔 NOTIFIKASI
        ========================= */

        // 📲 WHATSAPP
        if ($this->selectedPengajuan->user?->profil?->no_hp) {
            FonnteService::send(
                $this->selectedPengajuan->user->profil->no_hp,
                "❌ *SIMPEL-MRP*\n\n" .
                "Yth. Bapak/Ibu,\n\n" .
                "Mohon maaf, pengajuan\n" .
                "*Surat Keterangan Orang Asli Papua (OAP)* Anda\n" .
                "dinyatakan *DITOLAK*.\n\n" .
                "📝 *Alasan Penolakan:*\n" .
                "{$this->alasanPenolakan}\n\n" .
                "Jika membutuhkan informasi lebih lanjut,\n" .
                "silakan menghubungi petugas MRP Papua Tengah.\n\n" .
                "Terima kasih."
            );
        }

        // 📧 EMAIL
        if ($this->selectedPengajuan->user?->email) {
            Mail::to($this->selectedPengajuan->user->email)
                ->send(new NotifikasiPengajuanDitolak(
                    $this->selectedPengajuan,
                    $this->alasanPenolakan
                ));
        }

        /* ========================= */

        $this->showTolakModal = false;

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Pengajuan berhasil ditolak & notifikasi terkirim'
        ]);
    }

    public function render()
    {
        return view('livewire.petugas.verifikasi-surat-oap', [
            'pengajuans' => PengajuanSurat::whereIn('status', [
                'menunggu_verifikasi',
                'perlu_perbaikan',
                'berkas_lengkap',
                'draft_surat',
                'verifikasi',
            ])->latest()->get()
        ])->layout('components.layouts.app');
    }
}
