<?php

namespace App\Console\Commands;

use App\Models\PengajuanSurat;
use App\Models\VerifikasiPengajuan;
use Illuminate\Console\Command;

class ImportVerifikasiPengajuan extends Command
{
    protected $signature = 'import:verifikasi-pengajuan';
    protected $description = 'Import berkas lama ke tabel verifikasi_pengajuan';

    public function handle()
    {
        $this->info('🚀 Mulai import verifikasi pengajuan lama...');

        $pengajuanList = PengajuanSurat::whereIn('status', ['terbit', 'perlu_perbaikan', 'verifikasi'])
            ->get();

        $dokumenList = ['foto', 'ktp', 'kk', 'akte'];

        $count = 0;

        foreach ($pengajuanList as $pengajuan) {
            foreach ($dokumenList as $dokumen) {

                // skip jika kolom berkas kosong
                if (empty($pengajuan->{$dokumen})) {
                    continue;
                }

                // cegah duplikasi
                $exists = VerifikasiPengajuan::where('pengajuan_id', $pengajuan->id)
                    ->where('dokumen', $dokumen)
                    ->exists();

                if ($exists) {
                    continue;
                }

                VerifikasiPengajuan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'dokumen' => $dokumen,
                    'status' => 'valid', // asumsi berkas lama sudah sah
                    'catatan' => null,
                ]);

                $count++;
            }
        }

        $this->info("✅ Import selesai. Total berkas diimport: {$count}");
        return Command::SUCCESS;
    }
}
