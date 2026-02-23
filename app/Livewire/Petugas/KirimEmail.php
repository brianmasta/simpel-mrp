<?php

namespace App\Livewire\Petugas;

use App\Mail\NotifikasiPenggunaMail;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class KirimEmail extends Component
{
    public $user_id;
    public $judul;
    public $pesan;

    protected $rules = [
        'user_id' => 'required',
        'judul' => 'required|string',
        'pesan' => 'required|string',
    ];

    public function kirim()
    {
        $this->validate();

        $user = User::findOrFail($this->user_id);

        Mail::to($user->email)
            ->send(new NotifikasiPenggunaMail($this->judul, $this->pesan));

            // 📲 WHATSAPP DARI PROFIL
        if ($user->profil && $user->profil->no_hp) {

            FonnteService::send(
                $user->profil->no_hp,
                "*{$this->judul}*\n\n{$this->pesan}\n\n— SIMPEL-MRP"
                
            );
        }

        session()->flash('success', 'Email berhasil dikirim ke '.$user->email);

        $this->reset(['judul', 'pesan']);
    }

    public function render()
    {
        return view('livewire.petugas.kirim-email', [
            'users' => User::where('role', 'pengguna')->get()
        ]);
    }
}
