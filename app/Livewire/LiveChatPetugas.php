<?php

namespace App\Livewire;

use App\Models\LiveChat;
use Livewire\Component;

class LiveChatPetugas extends Component
{
    public $chat;
    public $reply;

    public function mount($chatId)
    {
        $this->chat = LiveChat::findOrFail($chatId);
    }

    public function sendReply()
    {
        $this->chat->messages()->create([
            'sender' => 'petugas',
            'sender_id' => auth()->id(),
            'message' => $this->reply
        ]);

        $this->reply = '';
    }

    public function render()
    {
        return view('livewire.live-chat-petugas', [
            'messages' => $this->chat->messages()->latest()->get()
        ])->layout('components.layouts.app'); // ⬅️ PENTING
    }
}
