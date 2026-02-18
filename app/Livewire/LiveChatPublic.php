<?php

namespace App\Livewire;

use App\Mail\LiveChatNotification;
use App\Models\LiveChat;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class LiveChatPublic extends Component
{
    public $chat;
    public $name, $email, $message;

    protected $rules = [
        'name' => 'required',
        'email' => 'nullable|email',
        'message' => 'required'
    ];

    public function startChat()
    {
        $this->validateOnly('name');

        $this->chat = LiveChat::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);
    }

    public function send()
    {
        // CEGAH KIRIM JIKA CHAT DITUTUP
        if (!$this->chat || $this->chat->status === 'closed') {
            return;
        }

        $this->validate();

        $this->chat->messages()->create([
            'sender' => 'user',
            'message' => $this->message,
        ]);

        Mail::to('brianmasta23@gmail.com')
    ->send(new LiveChatNotification($this->chat));

        $this->reset('message');
    }

    public function submitEmail()
    {
        $this->validate([
            'email' => 'required|email'
        ]);

        $this->chat->update([
            'email' => $this->email,
            'status' => 'open'
        ]);

        // PESAN SISTEM (REALTIME KE PETUGAS & PENGGUNA)
        $this->chat->messages()->create([
            'sender' => 'user',
            'message' => 'Alamat email berhasil diinput oleh pengguna.'
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Email berhasil disimpan. Petugas akan melanjutkan proses.'
        ]);
    }

    public function render()
    {
        if ($this->chat) {
            $this->chat->load('ticket');
        }

        return view('livewire.live-chat-public', [
            'messages' => $this->chat
                ? $this->chat->messages()->latest()->get()
                : []
        ]);
    }
}
