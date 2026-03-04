<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ForceChangePassword extends Component
{
    public $password;
    public $password_confirmation;

    public $showPassword = false;

    protected $rules = [
        'password' => 'required|min:6|confirmed',
    ];

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function simpan()
    {
        $this->validate();

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($this->password),
            'must_change_password' => false
        ]);

        session()->flash('success','Password berhasil diganti.');

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.force-change-password')
            ->layout('components.layouts.app');
    }
}
