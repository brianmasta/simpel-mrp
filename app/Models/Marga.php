<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marga extends Model
{
    use HasFactory;

    protected $fillable = [
        'wilayah_adat',
        'suku',
        'marga',
        'berkas',
    ];

    // Relasi ke user yang menambahkan data
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke history
    public function histories()
    {
        return $this->hasMany(MargaHistory::class);
    }
}
