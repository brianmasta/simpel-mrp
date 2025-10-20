<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        // Cek apakah email ada di database user
        if (!User::where('email', $this->email)->exists()) {
            $this->addError('email', 'Email ini belum terdaftar.');
            return;
        }

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', 'Link reset password sudah dikirim ke email Anda.');
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('components.layouts.auth');
    }
}
