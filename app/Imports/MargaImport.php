<?php

namespace App\Imports;

use App\Models\Marga;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MargaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Marga([
            'wilayah_adat' => $row[0],
            'suku' => $row[1],
            'marga' => $row[2],
        ]);
    }
}
