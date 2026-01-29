<?php

namespace App\Livewire;

use App\Models\FormatSurat as ModelsFormatSurat;
use App\Models\Profil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;

class FormatSurat extends Component
{
    public $jenis;
    public $isi_html = '';
    public $formatId = null;
    public $showPdfModal = false;
    public $pdfContent;
    public $daftarFormat = [];

    public function mount()
    {
        $this->daftarFormat = ModelsFormatSurat::latest()->get();
    }

    public function render()
    {
        return view('livewire.format-surat')->layout('components.layouts.app');
    }

    public function simpan()
    {
        $this->validate([
            'jenis' => 'required|string|max:255',
            'isi_html' => 'required|string',
        ]);

        ModelsFormatSurat::updateOrCreate(
            ['id' => $this->formatId],
            ['jenis' => $this->jenis, 'isi_html' => $this->isi_html]
        );

        session()->flash('success', $this->formatId ? 'Template berhasil diperbarui.' : 'Template berhasil disimpan.');

        // Refresh data daftar format
        $this->daftarFormat = ModelsFormatSurat::latest()->get();

        // Reset form
        $this->reset(['jenis', 'isi_html', 'formatId']);
    }

    public function edit($id)
    {
        $format = ModelsFormatSurat::findOrFail($id);
        $this->formatId = $format->id;
        $this->jenis = $format->jenis;
        $this->isi_html = $format->isi_html;
    }

    public function hapus($id)
    {
        ModelsFormatSurat::find($id)?->delete();
        session()->flash('success', 'Template berhasil dihapus.');

        $this->daftarFormat = ModelsFormatSurat::latest()->get();
    }

    public function preview($id)
    {
        $format = ModelsFormatSurat::findOrFail($id);
        $user = Profil::where('user_id', Auth::id())->with('kabupaten')->first();

        // 🔍 Ambil foto dari tabel pengajuan_surats (contoh: ambil pengajuan terakhir user)
        $pengajuan = \App\Models\PengajuanSurat::where('id', '22')->latest()->first();
        // dd($pengajuan = \App\Models\PengajuanSurat::where('id', '22')->latest()->first());
        // Jika pengajuan punya foto, tampilkan dari route private
        if ($pengajuan && $pengajuan->foto) {
            $filename = basename($pengajuan->foto); // ambil nama file saja
            // dd($filename);
            $fotoPath = url('/private/foto/' . $filename);
        } else {
            // fallback ke placeholder
            $fotoPath = url('/images/default-foto.jpg');
        }

        $data = [
            'nama_lengkap' => $user->nama_lengkap ?? 'Nama Pengguna',
            'nik' => $user->nik ?? '1234567890',
            'nomor_surat' => '002/MRP-PPT/X/2025',
            'kabupaten' => $user->kabupaten->nama ?? '',
            'nama_ayah' => $user->nama_ayah ?? '',
            'nama_ibu' => $user->nama_ibu ?? '',
            'foto' => $fotoPath,
            'keperluan' => 'Pendaftaran CPNS',
            'suku' => 'Biak',
            'tanggal' => now()->translatedFormat('d F Y'),
            'logo_transparan' => url('assets/img/logo.png'), // <-- logo watermark
        ];

        // Ganti placeholder dinamis di template
        $html = $format->isi_html;
        foreach ($data as $key => $value) {
            $html = str_replace('[[' . $key . ']]', $value, $html);
        }

        // Buat PDF preview
        $pdf = Pdf::loadHTML($html)->setPaper('A4', 'portrait');
        $this->pdfContent = base64_encode($pdf->output());

        $this->showPdfModal = true;
    }
}

