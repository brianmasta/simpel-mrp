<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tiket Kendala Dibuat – SIMPEL MRP')
            ->greeting('Yth. ' . $this->ticket->name)
            ->line('Kendala Anda memerlukan tindak lanjut lebih lanjut.')
            ->line('Tiket kendala telah dibuat dengan nomor:')
            ->line('**' . $this->ticket->ticket_number . '**')
            ->line('Petugas kami akan menindaklanjuti tiket tersebut.')
            ->salutation('Hormat kami,  
Majelis Rakyat Papua Provinsi Papua Tengah');
    }

}
