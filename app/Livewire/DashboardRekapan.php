<?php

namespace App\Livewire;

use App\Models\Marga;
use App\Models\PengajuanMarga;
use App\Models\PengajuanSurat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class DashboardRekapan extends Component
{
    public function render()
    {
        return view('livewire.dashboard-rekapan', [
            // Surat OAP
            'totalSurat'      => PengajuanSurat::count(),
            'suratTerbit'     => PengajuanSurat::where('status', 'terbit')->count(),
            'suratProses'     => PengajuanSurat::where('status', 'proses')->count(),
            'suratDitolak'    => PengajuanSurat::where('status', 'ditolak')->count(),

            // Marga
            'totalMarga'      => Marga::count(),

            // Akun
            'totalAkun'       => User::count(),
            'adminCount'      => User::where('role', 'admin')->count(),
            'petugasCount'    => User::where('role', 'petugas')->count(),
            'penggunaCount'   => User::where('role', 'pengguna')->count(),

            // data detail
            'suratList' => PengajuanSurat::with([
                'user.profil.kabupaten'
            ])
            ->latest()
            ->limit(50)
            ->get(),

            // Rekapan marga
            'margaList' => Marga::orderBy('marga')->get(),

            // Detail akun
            'akunList' => User::with('profil.kabupaten')
                ->orderBy('name')
                ->get(),
            ])->layout('components.layouts.app');

            
    }

    public function exportPdf()
    {
            $data = PengajuanSurat::with(['user.profil.kabupaten'])->get();

            $pdf = Pdf::loadView('pdf.rekapan-surat-oap', [
                'data' => $data,
                'tanggal' => now()->format('d M Y'),
            ])->setPaper('A4', 'landscape');

            return response()->streamDownload(
                fn () => print($pdf->output()),
                'rekapan-surat-oap.pdf'
            );
    }

    public function exportPdfMarga()
    {
        $data = Marga::orderBy('marga')->get();

        $pdf = Pdf::loadView('pdf.rekapan-data-marga', [
            'data' => $data,
            'tanggal' => now()->format('d M Y'),
        ])->setPaper('A4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rekapan-data-marga.pdf'
        );
    }

    public function exportPdfAkun()
    {
        $data = User::with('profil.kabupaten')
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('pdf.rekapan-data-akun', [
            'data' => $data,
            'tanggal' => now()->format('d M Y'),
        ])->setPaper('A4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rekapan-data-akun.pdf'
        );
    }
}
