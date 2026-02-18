<?php

namespace App\Livewire;

use App\Models\LiveChat;
use Livewire\Component;

class LiveChatBadge extends Component
{
    public function render()
    {
        return view('livewire.live-chat-badge', [
            'totalOpen' => LiveChat::where('status', 'open')->count()
        ]);
    }
}
