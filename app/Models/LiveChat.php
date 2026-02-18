<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveChat extends Model
{
    protected $fillable = [
        'name',
        'email',
        'status',
        'closed_reason',
        'closed_at',
    ];

    public function messages()
    {
        return $this->hasMany(LiveChatMessage::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'created_from_chat_id');
    }
}
