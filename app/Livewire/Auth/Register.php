<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    // CAPTCHA TOKEN
    public string $recaptchaToken = '';

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'name.required' => 'Nama lengkap wajib diisi.',
        'name.min' => 'Nama minimal 3 karakter.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sesuai.',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function register()
    {
        $validated = $this->validate();

        
        // 🔐 VALIDASI CAPTCHA
        if (!$this->validateRecaptcha()) {
            $this->addError('recaptcha', 'Silakan centang "Saya bukan robot".');
            $this->resetCaptcha();
            return;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pengguna',
        ]);

        Auth::login($user);

        session()->flash('success', 'Registrasi berhasil! Anda telah login.');

        return redirect()->route('dashboard');
    }

        /* ================= CAPTCHA ================= */

    private function validateRecaptcha(): bool
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

    private function resetCaptcha(): void
    {
        $this->recaptchaToken = '';
        $this->dispatch('reset-recaptcha');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('components.layouts.auth');
    }
}
