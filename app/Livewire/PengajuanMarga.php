<?php

namespace App\Livewire;

use App\Models\PengajuanMarga as ModelsPengajuanMarga;
use Illuminate\Support\Facades\Auth;
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

    public $pesanDuplikat;

    public $riwayat_pengajuan = [];

    public function mount()
    {
        $this->riwayat_pengajuan = ModelsPengajuanMarga::where('user_id', Auth::id())
            ->latest()
            ->get();
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

        // Cek duplikat
        $duplikat = ModelsPengajuanMarga::where('user_id', Auth::id())
            ->where('wilayah_adat', $this->wilayah_adat)
            ->where('suku', $this->suku)
            ->where('marga', $this->marga)
            ->first();

        if ($duplikat) {
            // Set property pesan untuk ditampilkan di view
            $this->pesanDuplikat = "⚠️ Marga '{$this->marga}' dengan suku '{$this->suku}' dan wilayah adat '{$this->wilayah_adat}' sudah diajukan sebelumnya.";
            return;
        } else {
            $this->pesanDuplikat = null; // hapus pesan jika tidak ada duplikat
        }

        $path = $this->berkas ? $this->berkas->store('berkas-pengajuan-marga', 'public') : null;

        $marga = ModelsPengajuanMarga::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => $this->nama_lengkap,
            'nik' => $this->nik,
            'wilayah_adat' => $this->wilayah_adat,
            'suku' => $this->suku,
            'marga' => $this->marga,
            'alasan' => $this->alasan,
            'berkas' => $path,
        ]);

        logActivity(
            'Mengajukan marga baru: ' . strtoupper($marga->marga),
            $marga
        );

        session()->flash('success', 'Pengajuan marga Anda telah dikirim dan menunggu verifikasi MRP.');
                    $this->dispatch('toast', [
                'message' => "Pengajuan marga Anda telah dikirim dan menunggu verifikasi MRP.",
                'type' => 'success'
            ]);

        $this->resetExcept('riwayat_pengajuan');

        // Refresh riwayat pengajuan milik user
        $this->riwayat_pengajuan = ModelsPengajuanMarga::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.pengajuan-marga')->layout('components.layouts.app');
    }
}
