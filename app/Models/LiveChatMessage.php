<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    protected $fillable = [
        'live_chat_id',
        'sender',
        'sender_id',
        'message'
    ];

    public function chat()
    {
        return $this->belongsTo(LiveChat::class);
    }
}
