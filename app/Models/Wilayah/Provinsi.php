<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $fillable = ['nama'];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class);
    }
}
