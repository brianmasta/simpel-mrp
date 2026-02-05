<?php

namespace App\Livewire;

use App\Mail\NotifikasiPerluPerbaikan;
use App\Models\FormatSurat;
use App\Models\Marga;
use App\Models\PengajuanSurat;
use App\Models\Profil;
use App\Models\VerifikasiPengajuan;
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class VerifikasiBerkas extends Component
{
    public $selectedData = null;

    public $selectedId = null;
    public $catatan = [];

    public function pilih($id)
    {
        $this->selectedId = $id;

        $verifikasi = VerifikasiPengajuan::where('pengajuan_id', $id)->get();
        foreach ($verifikasi as $v) {
            $this->catatan[$v->dokumen] = $v->catatan;

                $this->selectedData = PengajuanSurat::with('verifikasi')->findOrFail($id);
        }
    }

    public function setStatus($verifikasiId, $status)
    {
        $verifikasi = VerifikasiPengajuan::findOrFail($verifikasiId);

        $verifikasi->update([
            'status' => $status,
        ]);

        // ⬅️ WAJIB
        $this->updateStatusPengajuan($verifikasi->pengajuan_id);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Status berkas diperbarui'
        ]);
    }

    public function simpanCatatan($verifikasiId, $dokumen)
    {
        VerifikasiPengajuan::where('id', $verifikasiId)->update([
            'catatan' => $this->catatan[$dokumen] ?? null,
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Catatan disimpan'
        ]);
    }

    /**
     * 🔑 LOGIKA STATUS GLOBAL PENGAJUAN
     */
    protected function updateStatusPengajuan($pengajuanId)
    {
        $total = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)->count();

        $valid = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
            ->where('status', 'valid')
            ->count();

        $perluPerbaikan = VerifikasiPengajuan::where('pengajuan_id', $pengajuanId)
            ->where('status', 'perlu_perbaikan')
            ->exists();

        $pengajuan = PengajuanSurat::findOrFail($pengajuanId);

        // 🔴 PRIORITAS 1: ADA PERBAIKAN
        if ($perluPerbaikan) {

    // simpan status lama
    $oldStatus = $pengajuan->status;

    // update status
    $pengajuan->status = 'perlu_perbaikan';
    $pengajuan->pernah_perbaikan = true;
    $pengajuan->save();

    // kirim WA hanya jika status BARU
    if ($oldStatus !== 'perlu_perbaikan') {

        // =========================
        // 📝 AMBIL CATATAN PETUGAS
        // =========================
        $catatanList = VerifikasiPengajuan::where('pengajuan_id', $pengajuan->id)
            ->where('status', 'perlu_perbaikan')
            ->whereNotNull('catatan')
            ->get()
            ->map(fn ($v) => '- ' . strtoupper($v->dokumen) . ': ' . $v->catatan)
            ->implode("\n");

        if ($catatanList === '') {
            $catatanList = '- Silakan lihat detail di sistem SIMPEL-MRP';
        }

        // 🔗 LINK PERBAIKAN
        $linkPerbaikan = url('/perbaikan-berkas/' . $pengajuan->id);

        // =========================
        // 📲 KIRIM WHATSAPP
        // =========================
        if ($pengajuan->user?->profil?->no_hp) {
            FonnteService::send(
                $pengajuan->user->profil->no_hp,
                "⚠️ *SIMPEL-MRP*\n\n" .
                "Yth. Bapak/Ibu,\n\n" .
                "Berdasarkan hasil verifikasi petugas,\n" .
                "pengajuan *Surat Keterangan Orang Asli Papua (OAP)* Anda\n" .
                "berstatus *PERLU PERBAIKAN*.\n\n" .
                "📝 *Catatan Petugas:*\n" .
                "{$catatanList}\n\n" .
                "Silakan melakukan perbaikan melalui tautan berikut:\n" .
                "🔗 {$linkPerbaikan}\n\n" .
                "Terima kasih atas perhatian dan kerja samanya."
            );
        }

                // 📧 EMAIL
        if ($pengajuan->user?->email) {
            Mail::to($pengajuan->user->email)
                ->send(new NotifikasiPerluPerbaikan($pengajuan));
        }
    }

    return;
        }

        // 🟡 BELUM SEMUA VALID
        if ($valid < $total) {
            $pengajuan->status = 'verifikasi';
            $pengajuan->save();
            return;
        }

        // 🟢 SEMUA VALID
        // =========================================
        // KASUS A: tidak pernah perbaikan → TIDAK UBah surat
        if (!$pengajuan->pernah_perbaikan) {
            $pengajuan->status = 'terbit';
            $pengajuan->save();
            return;
        }

        // 🔁 KASUS B: PERNAH PERBAIKAN → TERBIT ULANG
        $this->terbitkanSuratOap($pengajuan, true);
    }

    /**
     * ===============================
     * TERBITKAN / TERBIT ULANG SURAT OAP
     * ===============================
     */
    protected function terbitkanSuratOap(PengajuanSurat $pengajuan, bool $isRevisi = false)
    {
        $profil = Profil::where('user_id', $pengajuan->user_id)
            ->with('kabupaten')
            ->firstOrFail();

        $format = FormatSurat::where('jenis', 'surat_oap')->first();
        if (!$format) {
            return;
        }

        $tahun = now()->year;

        /**
         * ===============================
         * NOMOR SURAT
         * ===============================
         */
        if ($isRevisi) {
            // 🔁 Revisi → pakai nomor lama
            $nomorSurat = $pengajuan->nomor_surat;
        } else {
            // 🆕 Terbit pertama
            DB::transaction(function () use (&$nomorSurat, $tahun) {
                $last = PengajuanSurat::lockForUpdate()
                    ->whereYear('created_at', $tahun)
                    ->selectRaw("MAX(CAST(SUBSTRING_INDEX(nomor_surat,'/',1) AS UNSIGNED)) as max_no")
                    ->first();

                $next = ($last->max_no ?? 0) + 1;
                $nomorUrut = str_pad($next, 5, '0', STR_PAD_LEFT);

                $nomorSurat = "{$nomorUrut}/OAP/MRP-PPT";
            });
        }

        /**
         * ===============================
         * KODE AUTENTIKASI & QR
         * ===============================
         */
        if ($isRevisi && $pengajuan->kode_autentikasi) {
            $kodeAutentikasi = $pengajuan->kode_autentikasi;
        } else {
            $kodeAutentikasi = strtoupper(substr(md5($profil->nik . now()), 0, 10));
        }

        $verifyUrl = url("/verifikasi-surat/{$kodeAutentikasi}");

        $qrFileName = "qrcode_{$kodeAutentikasi}.png";
        $qrDirectory = public_path('qrcodes');
        if (!File::exists($qrDirectory)) {
            File::makeDirectory($qrDirectory, 0755, true);
        }
        $qrAbsolutePath = $qrDirectory . '/' . $qrFileName;

        $qr = \Endroid\QrCode\Builder\Builder::create()
            ->writer(new \Endroid\QrCode\Writer\PngWriter())
            ->data($verifyUrl)
            ->size(200)
            ->margin(10)
            ->build();

        $qr->saveToFile($qrAbsolutePath);

        /**
         * ===============================
         * FOTO PRIVATE
         * ===============================
         */
        $fotoFullPath = storage_path('app/private/' . $pengajuan->foto);
        $fotoBase64 = file_exists($fotoFullPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fotoFullPath))
            : '';

        /**
         * ===============================
         * DATA TEMPLATE
         * ===============================
         */
        $parts = explode(' ', trim($profil->nama_lengkap));
        $marga = ucfirst(strtolower(end($parts)));
        $cekMarga = Marga::where('marga', $marga)->first();

        $data = [
            'nama_lengkap' => $profil->nama_lengkap,
            'nik' => $profil->nik,
            'nik_kk' => $profil->no_kk,
            'nomor_surat' => $nomorSurat,
            'kabupaten' => $profil->kabupaten->nama ?? '',
            'nama_ayah' => $profil->nama_ayah,
            'nama_ibu' => $profil->nama_ibu,
            'alasan' => $pengajuan->alasan,
            'suku' => $cekMarga->suku ?? '',
            'foto' => $fotoBase64,
            'qrcode' => 'data:image/png;base64,' . base64_encode(file_get_contents($qrAbsolutePath)),
            'tanggal' => now()->translatedFormat('d F Y'),
        ];

        $html = $format->isi_html;
        foreach ($data as $key => $value) {
            $html = str_replace('[[' . $key . ']]', $value, $html);
        }

        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');

        /**
         * ===============================
         * FILE PDF
         * ===============================
         */
        $fileName = $isRevisi && $pengajuan->file_surat
            ? basename($pengajuan->file_surat)
            : 'surat_oap_' . $profil->nik . '_' . time() . '.pdf';

        $relativePath = 'surat_oap/' . $fileName;

        Storage::disk('public')->put($relativePath, $pdf->output());

        /**
         * ===============================
         * UPDATE DATABASE
         * ===============================
         */
        $pengajuan->update([
            'nomor_surat' => $nomorSurat,
            'file_surat' => $relativePath,
            'status' => 'terbit',
            'kode_autentikasi' => $kodeAutentikasi,
            'qr_code_path' => $relativePath,
        ]);
    }

    public function render()
    {
        return view('livewire.verifikasi-berkas', [
            'pengajuan' => PengajuanSurat::latest()->get()
        ]);
    }
}
