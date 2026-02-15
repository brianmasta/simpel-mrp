<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    public string $recaptchaToken = '';

    public function login()
    {
        // Validasi form
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        
        if (!$this->validateRecaptcha()) {
            $this->addError('recaptcha', 'Silakan centang "Saya bukan robot".');
            $this->resetCaptcha();
            return;
        }

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


    private function validateRecaptcha()
    {
        if (empty($this->recaptchaToken)) {
            return false;
        }

        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $this->recaptchaToken,
            ]
        );

        return $response->json('success') === true;
    }

    private function resetCaptcha()
    {
        $this->recaptchaToken = '';
        $this->dispatch('reset-recaptcha');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.auth');
    }
}
