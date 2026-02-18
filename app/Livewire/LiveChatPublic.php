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
        $this->validate();

        $this->chat->messages()->create([
            'sender' => 'user',
            'message' => $this->message,
        ]);

        Mail::to('brianmasta23@gmail.com')
    ->send(new LiveChatNotification($this->chat));

        $this->reset('message');
    }

    public function render()
    {
        return view('livewire.live-chat-public', [
            'messages' => $this->chat
                ? $this->chat->messages()->latest()->get()
                : []
        ]);
    }
}
