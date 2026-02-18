<?php

namespace App\Livewire;

use App\Models\LiveChat;
use Livewire\Component;

class LiveChatIndex extends Component
{
    public function render()
    {
        return view('livewire.admin.live-chat-index', [
            'chats' => LiveChat::latest()->get()
        ])->layout('components.layouts.app');
    }
}
