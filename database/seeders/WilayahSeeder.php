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
        $baseUrl = 'https://ibnux.github.io/data-indonesia';

        // Daftar ID provinsi untuk Pulau Papua
        $papuaProvinceIds = [
            '94', // Papua
            '92', // Papua Barat
            '95', // Papua Tengah
            '96', // Papua Pegunungan
            '97', // Papua Selatan
            '98', // Papua Barat Daya
        ];

        // Ambil semua provinsi
        $allProv = Http::get("{$baseUrl}/provinsi.json")->json();

        foreach ($allProv as $prov) {
            if (!in_array($prov['id'], $papuaProvinceIds)) continue;

            $p = Provinsi::updateOrCreate(
                ['id' => $prov['id']],
                ['nama' => $prov['nama']]
            );

            $this->command->info("Memproses Provinsi: {$prov['nama']}");

            // Ambil kabupaten
            $kabs = $this->fetchJson("{$baseUrl}/kabupaten/{$prov['id']}.json");
            foreach ($kabs as $kab) {
                $k = Kabupaten::updateOrCreate(
                    ['id' => $kab['id']],
                    [
                        'provinsi_id' => $p->id,
                        'nama' => $kab['nama']
                    ]
                );

                $this->command->info("  ↳ Kabupaten: {$kab['nama']}");

                // Ambil kecamatan
                $kecs = $this->fetchJson("{$baseUrl}/kecamatan/{$kab['id']}.json");
                foreach ($kecs as $kec) {
                    $kc = Kecamatan::updateOrCreate(
                        ['id' => $kec['id']],
                        [
                            'kabupaten_id' => $k->id,
                            'nama' => $kec['nama']
                        ]
                    );

                    // Ambil kelurahan
                    $desas = $this->fetchJson("{$baseUrl}/kelurahan/{$kec['id']}.json");
                    foreach ($desas as $desa) {
                        Kelurahan::updateOrCreate(
                            ['id' => $desa['id']],
                            [
                                'kecamatan_id' => $kc->id,
                                'nama' => $desa['nama']
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Wilayah Pulau Papua berhasil di-seed.');
    }

    private function fetchJson(string $url)
    {
        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            $this->command->warn("Gagal mengambil data dari: $url");
        }
        return [];
    }
}
