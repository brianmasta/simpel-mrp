<?php

namespace App\Livewire;

use App\Models\PengajuanSurat;
use Livewire\Component;

class VerifikasiSurat extends Component
{
    public $kode;
    public $surat;
    public $status;

    public function mount($kode)
    {
        $this->kode = $kode;
        $this->surat = PengajuanSurat::with('profil')->where('kode_autentikasi', $kode)->first();

        $this->status = $this->surat ? 'valid' : 'invalid';
    }

    public function render()
    {
        return view('livewire.verifikasi-surat')->layout('components.layouts.auth');
    }
}
