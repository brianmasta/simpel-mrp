<?php

namespace App\Notifications;

use App\Models\LiveChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatClosedNotification extends Notification
{
    use Queueable;

    public function __construct(public LiveChat $chat) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Masalah Anda Telah Ditangani – SIMPEL MRP')
            ->greeting('Yth. ' . $this->chat->name)
            ->line('Terima kasih telah menghubungi layanan SIMPEL-MRP.')
            ->line('Masalah yang Anda sampaikan melalui live chat telah berhasil ditangani oleh petugas kami.')
            ->line('Jika masih ada kendala, silakan menghubungi kembali melalui aplikasi.')
            ->salutation('Hormat kami,  
Majelis Rakyat Papua Provinsi Papua Tengah');
    }
}
