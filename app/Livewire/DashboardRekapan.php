<?php

namespace App\Livewire;

use App\Models\Marga;
use App\Models\PengajuanMarga;
use App\Models\PengajuanSurat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardRekapan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /* ======================
     | FILTER SURAT
     ====================== */
    public $searchSurat = '';
    public $statusSurat = '';

    /* ======================
     | FILTER MARGA
     ====================== */
    public $searchMarga = '';
    public $wilayahAdat = '';

    /* ======================
     | FILTER AKUN
     ====================== */
    public $searchAkun = '';
    public $roleAkun = '';

    protected $queryString = [
        'searchSurat',
        'statusSurat',
        'searchMarga',
        'wilayahAdat',
        'searchAkun',
        'roleAkun',
    ];

    public function updating()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.dashboard-rekapan', [

            /* ===== KARTU STATISTIK ===== */
            'totalSurat'        => PengajuanSurat::count(),
            'suratTerbit'       => PengajuanSurat::where('status', 'terbit')->count(),
            'suratPerbaikan'    => PengajuanSurat::where('status', 'perlu_perbaikan')->count(),
            'suratDitolak'      => PengajuanSurat::where('status', 'ditolak')->count(),

            'totalMarga'        => Marga::count(),

            'totalAkun'         => User::count(),
            'adminCount'        => User::where('role', 'admin')->count(),
            'petugasCount'      => User::where('role', 'petugas')->count(),
            'penggunaCount'     => User::where('role', 'pengguna')->count(),

            /* ===== TABEL SURAT ===== */
            'suratList' => PengajuanSurat::with('user.profil.kabupaten')
                ->when($this->searchSurat, function ($q) {
                    $q->whereHas('user.profil', function ($p) {
                        $p->where('nama_lengkap', 'like', '%' . $this->searchSurat . '%')
                          ->orWhere('nik', 'like', '%' . $this->searchSurat . '%');
                    });
                })
                ->when($this->statusSurat, fn ($q) =>
                    $q->where('status', $this->statusSurat)
                )
                ->latest()
                ->paginate(10, pageName: 'suratPage'),

            /* ===== TABEL MARGA ===== */
            'margaList' => Marga::when($this->searchMarga, fn ($q) =>
                    $q->where('marga', 'like', '%' . $this->searchMarga . '%')
                      ->orWhere('suku', 'like', '%' . $this->searchMarga . '%')
                )
                ->when($this->wilayahAdat, fn ($q) =>
                    $q->where('wilayah_adat', $this->wilayahAdat)
                )
                ->orderBy('marga')
                ->paginate(10, pageName: 'margaPage'),

            /* ===== TABEL AKUN ===== */
            'akunList' => User::with('profil.kabupaten')
                ->when($this->searchAkun, fn ($q) =>
                    $q->where('name', 'like', '%' . $this->searchAkun . '%')
                      ->orWhere('email', 'like', '%' . $this->searchAkun . '%')
                )
                ->when($this->roleAkun, fn ($q) =>
                    $q->where('role', $this->roleAkun)
                )
                ->orderBy('name')
                ->paginate(10, pageName: 'akunPage'),

            'rekapanWilayahAdat' => Marga::select('wilayah_adat', DB::raw('COUNT(*) as total'))
                ->whereIn('wilayah_adat', [
                    'Mamta/Tabi',
                    'Saireri',
                    'Domberai',
                    'Bomberai',
                    'Meepago',
                    'La Pago',
                    'Ha Anim',
                ])
                ->groupBy('wilayah_adat')
                ->pluck('total', 'wilayah_adat'),

            'totalMarga' => Marga::count(),

        ])->layout('components.layouts.app');
    }

    /* ======================
     | EXPORT PDF (FULL DATA)
     ====================== */

    public function exportPdf()
    {
        $data = PengajuanSurat::with('user.profil.kabupaten')->get();

        return response()->streamDownload(
            fn () => print(
                Pdf::loadView('pdf.rekapan-surat-oap', [
                    'data' => $data,
                    'tanggal' => now()->format('d M Y'),
                ])->setPaper('A4', 'landscape')->output()
            ),
            'rekapan-surat-oap.pdf'
        );
    }

    public function exportPdfMarga()
    {
        $data = Marga::orderBy('marga')->get();

        return response()->streamDownload(
            fn () => print(
                Pdf::loadView('pdf.rekapan-data-marga', [
                    'data' => $data,
                    'tanggal' => now()->format('d M Y'),
                ])->setPaper('A4', 'landscape')->output()
            ),
            'rekapan-data-marga.pdf'
        );
    }

    public function exportPdfAkun()
    {
        $data = User::with('profil.kabupaten')->orderBy('name')->get();

        return response()->streamDownload(
            fn () => print(
                Pdf::loadView('pdf.rekapan-data-akun', [
                    'data' => $data,
                    'tanggal' => now()->format('d M Y'),
                ])->setPaper('A4', 'landscape')->output()
            ),
            'rekapan-data-akun.pdf'
        );
    }

}
