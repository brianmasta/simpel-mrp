<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveChat extends Model
{
    protected $fillable = ['name','email','status'];

    public function messages()
    {
        return $this->hasMany(LiveChatMessage::class);
    }
}
