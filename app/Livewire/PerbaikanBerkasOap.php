<?php

namespace App\Livewire;

use App\Models\PengajuanSurat;
use App\Models\VerifikasiPengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PerbaikanBerkasOap extends Component
{
    use WithFileUploads;

    public $pengajuan;
    public $perluPerbaikan;
    public $upload = [];
    public $berkasPerluPerbaikan;

    public function mount($id)
    {
        $this->pengajuan = PengajuanSurat::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 🔒 1. pastikan milik user login
        if ($this->pengajuan->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }

        // 🔒 2. status HARUS perlu_perbaikan
        if ($this->pengajuan->status !== 'perlu_perbaikan') {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'Tidak ada permintaan perbaikan berkas.'
            ]);
            return redirect()->route('dashboard'); // sesuaikan
        }

        // 🔒 3. cek ada berkas perlu perbaikan
        $this->berkasPerluPerbaikan = VerifikasiPengajuan::where('pengajuan_id', $id)
            ->where('status', 'perlu_perbaikan')
            ->get();

        // Hanya dokumen yang perlu perbaikan
        $this->perluPerbaikan = VerifikasiPengajuan::where('pengajuan_id', $id)
            ->where('status', 'perlu_perbaikan')
            ->get();
    }

    public function submit()
    {
        foreach ($this->perluPerbaikan as $v) {

            if (!isset($this->upload[$v->dokumen])) {
                continue;
            }

            // hapus file lama
            if ($this->pengajuan->{$v->dokumen}) {
                Storage::delete($this->pengajuan->{$v->dokumen});
            }

            // simpan file baru
            $path = $this->upload[$v->dokumen]
                ->store("public/surat_oap/{$v->dokumen}");

            // update di pengajuan
            $this->pengajuan->update([
                $v->dokumen => $path
            ]);

            // reset status verifikasi dokumen
            $v->update([
                'status' => 'menunggu'
            ]);
        }

        // update status pengajuan
        $this->pengajuan->update([
            'status' => 'verifikasi'
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => '✅ Berkas perbaikan berhasil dikirim. Menunggu verifikasi ulang.'
        ]);

        return redirect()->route('surat-oap'); // sesuaikan
    }

    public function render()
    {
        return view('livewire.perbaikan-berkas-oap')->layout('components.layouts.app');
    }
}
