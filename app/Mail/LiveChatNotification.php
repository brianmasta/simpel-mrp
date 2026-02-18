<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LiveChatNotification extends Mailable
{
    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    public function build()
    {
        return $this->subject('Live Chat Masuk - SIMPEL MRP')
            ->view('emails.live-chat');
    }
}
