<?php

namespace App\Livewire;

use App\Models\Marga;
use App\Models\PengajuanMarga;
use Livewire\Component;

class Verifikasi extends Component
{
    public $pengajuans;
    public $selectedId;
    public $catatan;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->pengajuans = PengajuanMarga::orderBy('created_at', 'desc')->get();
    }

    public function setuju($id)
    {
        $pengajuan = PengajuanMarga::find($id);

        if (!$pengajuan) {
            session()->flash('error', 'Data pengajuan tidak ditemukan.');
            return;
        }

        // Cegah duplikasi jika marga sudah ada di tabel margas
        $margaSudahAda = Marga::where('marga', $pengajuan->marga)->exists();

        if (!$margaSudahAda) {
            // Tambahkan ke tabel margas
            Marga::create([
                'marga' => $pengajuan->marga,
                'suku' => $pengajuan->suku,
                'wilayah_adat' => $pengajuan->wilayah_adat,
            ]);
        }

        // Update status pengajuan
        $pengajuan->update([
            'status' => 'disetujui',
            'catatan_verifikasi' => $this->catatan ?? null,
        ]);

        session()->flash('success', 'Pengajuan marga telah disetujui dan marga berhasil dimasukkan ke database utama.');
        $this->loadData();
    }

    public function tolak($id)
    {
        $marga = PengajuanMarga::find($id);
        $marga->update([
            'status' => 'ditolak',
            'catatan_verifikasi' => $this->catatan,
        ]);

        session()->flash('error', 'Pengajuan marga telah ditolak.');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.verifikasi');
    }
}
