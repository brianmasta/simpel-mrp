<?php

namespace App\Livewire;

use App\Models\PengajuanMarga as ModelsPengajuanMarga;
use Livewire\Component;
use Livewire\WithFileUploads;

class PengajuanMarga extends Component
{
    use WithFileUploads;

    public $nama_lengkap;
    public $nik;
    public $wilayah_adat;
    public $suku;
    public $marga;
    public $alasan;
    public $berkas;

    public $riwayat_pengajuan = [];

    public function mount()
    {
        // Menampilkan semua pengajuan milik user (bisa difilter berdasarkan NIK nanti)
        $this->riwayat_pengajuan = ModelsPengajuanMarga::latest()->get();
    }

    public function save()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|numeric|digits:16',
            'wilayah_adat' => 'required|string|max:255',
            'suku' => 'required|string|max:255',
            'marga' => 'required|string|max:255',
            'alasan' => 'nullable|string',
            'berkas' => 'nullable|file|max:2048|mimes:pdf,jpg,png',
        ]);

        $path = $this->berkas ? $this->berkas->store('berkas-pengajuan-marga', 'public') : null;

        ModelsPengajuanMarga::create([
            'nama_lengkap' => $this->nama_lengkap,
            'nik' => $this->nik,
            'wilayah_adat' => $this->wilayah_adat,
            'suku' => $this->suku,
            'marga' => $this->marga,
            'alasan' => $this->alasan,
            'berkas' => $path,
        ]);

        session()->flash('success', 'Pengajuan marga Anda telah dikirim dan menunggu verifikasi MRP.');

        $this->resetExcept('riwayat_pengajuan');

        // Refresh data riwayat
        $this->riwayat_pengajuan = ModelsPengajuanMarga::latest()->get();
    }

    public function render()
    {
        return view('livewire.pengajuan-marga');
    }
}
