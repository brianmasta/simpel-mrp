<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailId extends VerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Verifikasi Alamat Email Anda')
            ->greeting('Halo!')
            ->line('Terima kasih telah mendaftar di SIMPEL-MRP.')
            ->line('Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
            ->action('Verifikasi Email', $url)
            ->line('Jika Anda tidak melakukan pendaftaran, abaikan email ini.');
    }
}
