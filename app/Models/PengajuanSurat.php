<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'alasan',
        'foto',
        'ktp',
        'kk',
        'akte',
        'file_surat',
        'status',
        'kode_autentikasi',
        'qr_code_path',    
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profil()
    {
        return $this->belongsTo(\App\Models\Profil::class, 'user_id', 'user_id');
    }
}
