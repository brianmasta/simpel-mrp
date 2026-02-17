<?php

namespace App\Livewire;

use App\Models\Marga;
use App\Models\PengajuanMarga;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Verifikasi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterStatus = '';

    public $pengajuans;
    public $selectedId;
    public $catatan;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->pengajuans = PengajuanMarga::orderBy('created_at', 'desc')->get();
    }

    public function setuju($id)
    {
        $pengajuan = PengajuanMarga::find($id);

        if (!$pengajuan) {
            session()->flash('error', 'Data pengajuan tidak ditemukan.');
            return;
        }

        // Cegah duplikasi jika marga sudah ada di tabel margas
        $margaSudahAda = Marga::where('marga', $pengajuan->marga)
            ->where('suku', $pengajuan->suku)
            ->where('wilayah_adat', $pengajuan->wilayah_adat)
            ->exists();

        if ($margaSudahAda) {
            // Jika sudah ada, langsung ditolak
            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_verifikasi' => '⚠️ Pengajuan marga sudah ada di database utama.',
            ]);

            session()->flash('error', "Pengajuan marga '{$pengajuan->marga}' sudah ada, sehingga otomatis ditolak.");
            $this->loadData();
            return;
        }

        // Jika belum ada, tambah ke tabel Marga
        $marga = Marga::create([
            'marga' => $pengajuan->marga,
            'suku' => $pengajuan->suku,
            'wilayah_adat' => $pengajuan->wilayah_adat,
        ]);

        // Update status pengajuan
        $pengajuan->update([
            'status' => 'disetujui',
            'catatan_verifikasi' => $this->catatan ?? null,
        ]);

        logActivity('Pengajuan marga telah disetujui ' . strtoupper($marga->marga), $marga);

        $this->dispatch('toast', [
                'message' => "Pengajuan marga telah disetujui dan marga berhasil dimasukkan ke database utama.",
                'type' => 'success'
            ]);
        session()->flash('success', 'Pengajuan marga telah disetujui dan marga berhasil dimasukkan ke database utama.');
        $this->loadData();
    }

    public function tolak($id)
    {
        $marga = PengajuanMarga::find($id);
        $marga->update([
            'status' => 'ditolak',
            'catatan_verifikasi' => $this->catatan,
        ]);

        logActivity('Pengajuan marga telah ditolak: ' . strtoupper($marga->marga), $marga);

        $this->dispatch('toast', [
            'message' => "Pengajuan marga telah ditolak.",
            'type' => 'warning'
        ]);

        session()->flash('error', 'Pengajuan marga telah ditolak.');
        $this->loadData();
    }

    public function exportExcel()
    {
        $fileName = 'data_pengajuan_marga_oap_' . now()->format('Ymd_His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Marga');
        $sheet->setCellValue('C1', 'Suku');
        $sheet->setCellValue('D1', 'wilayah_adat');
        $sheet->setCellValue('E1', 'status');
        $sheet->setCellValue('F1', 'Tanggal Pengajuan');

        // Ambil data sesuai filter/search
        $query = PengajuanMarga::query();

        if ($this->search) {
            $query->where('nama_lengkap', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->orWhere('suku', 'like', "%{$this->search}%")
                ->orWhere('wilayah_adat', 'like', "%{$this->search}%")
                ->orWhere('marga', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $item->nama_lengkap ?? '-');
            $sheet->setCellValue("C{$row}", $item->nik ?? '-');
            $sheet->setCellValue("D{$row}", $item->marga ?? '-');
            $sheet->setCellValue("E{$row}", $item->status ?? '-');
            $sheet->setCellValue("F{$row}", optional($item->created_at)->format('Y-m-d H:i') ?? '-');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="data_pengajuan_marga_oap_' . now()->format('Ymd_His') . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function render()
    {
        $query = PengajuanMarga::query();

        // 🔍 Pencarian berdasarkan profil
        if ($this->search) {
            $query->where('nama_lengkap', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->orWhere('suku', 'like', "%{$this->search}%")
                ->orWhere('wilayah_adat', 'like', "%{$this->search}%")
                ->orWhere('marga', 'like', "%{$this->search}%");
            }

        // 🔽 Urutan data terbaru dulu
        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.verifikasi', [
            'pengajuan' => $pengajuan,
        ])->layout('components.layouts.app');
    }
}
