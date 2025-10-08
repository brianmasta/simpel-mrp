<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MargaHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'marga_id',
        'user_id',
        'action',
    ];

    public function marga()
    {
        return $this->belongsTo(Marga::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
