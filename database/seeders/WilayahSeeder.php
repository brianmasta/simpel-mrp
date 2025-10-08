<?php

namespace Database\Seeders;

use App\Models\Wilayah\Kabupaten;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kelurahan;
use App\Models\Wilayah\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsis = Http::get('https://ibnux.github.io/data-indonesia/provinsi.json')->json();
        foreach ($provinsis as $prov) {
            $p = Provinsi::create(['id' => $prov['id'], 'nama' => $prov['nama']]);

            $kabs = Http::get("https://ibnux.github.io/data-indonesia/kabupaten/{$prov['id']}.json")->json();
            foreach ($kabs as $kab) {
                $k = Kabupaten::create([
                    'id' => $kab['id'],
                    'provinsi_id' => $p->id,
                    'nama' => $kab['nama']
                ]);

                $kecs = Http::get("https://ibnux.github.io/data-indonesia/kecamatan/{$kab['id']}.json")->json();
                foreach ($kecs as $kec) {
                    $kc = Kecamatan::create([
                        'id' => $kec['id'],
                        'kabupaten_id' => $k->id,
                        'nama' => $kec['nama']
                    ]);

                    $desas = Http::get("https://ibnux.github.io/data-indonesia/kelurahan/{$kec['id']}.json")->json();
                    foreach ($desas as $desa) {
                        Kelurahan::create([
                            'id' => $desa['id'],
                            'kecamatan_id' => $kc->id,
                            'nama' => $desa['nama']
                        ]);
                    }
                }
            }
        }
    }
}
