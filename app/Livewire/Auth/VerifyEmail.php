<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class VerifyEmail extends Component
{
    public ?string $message = null;
    public int $cooldown = 0; // detik

    public function mount()
    {
        $key = $this->rateLimitKey();
        $this->cooldown = RateLimiter::availableIn($key);
    }

    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        
        $key = $this->rateLimitKey();

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $this->cooldown = RateLimiter::availableIn($key);
            return;
        }

        RateLimiter::hit($key, 60); // ✅ 60 DETIK FIX

        Auth::user()->sendEmailVerificationNotification();

        $this->message = "Link verifikasi baru telah dikirim ke email Anda.";


        $this->cooldown = 60;
    }

        // Dipanggil tiap 1 detik via wire:poll
    public function tick()
    {
        $this->cooldown = RateLimiter::availableIn($this->rateLimitKey());
    }

    protected function rateLimitKey(): string
    {
        return 'email-verification:' . Auth::id();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.verify-email')
            ->layout('components.layouts.auth'); 
    }
}
