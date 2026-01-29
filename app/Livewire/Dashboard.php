<?php

namespace App\Livewire;

use App\Models\Profil;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;



class Dashboard extends Component
{
    public $lengkap = false;

    public function mount()
    {
        $user = Auth::user();

        // Cek apakah profil user ada di tabel profils
        $profil = Profil::where('user_id', $user->id)->first();

        // Jika profil ditemukan -> lengkap = true
        $this->lengkap = $profil ? true : false;
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app');
    }
}
