<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number',
        'name',
        'email',
        'subject',
        'description',
        'status',
        'created_from_chat_id',
        'handled_by'
    ];

    public function chat()
    {
        return $this->belongsTo(LiveChat::class, 'created_from_chat_id');
    }
}
