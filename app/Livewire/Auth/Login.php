<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public function login()
    {
        // Validasi form
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        // Auth::attempt dengan opsi remember
        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            // Ambil role user
            $role = Auth::user()->role;

            // Redirect sesuai role
            return match ($role) {
                'admin'   => redirect()->route('dashboard'),
                'petugas' => redirect()->route('dashboard'),
                'pengguna' => redirect()->route('dashboard'),
            };
        }

        $this->addError('email', 'Email atau password salah.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.auth');
    }
}
