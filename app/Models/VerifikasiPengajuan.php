<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiPengajuan extends Model
{
    protected $table = 'verifikasi_pengajuan';

    protected $fillable = [
        'pengajuan_id',
        'dokumen',
        'status',
        'catatan'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }
}
