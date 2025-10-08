<?php

namespace App\Livewire;

use App\Models\PengajuanSurat;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengajuanSuratExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataSuratOap extends Component
{
    public $previewPdfUrl = null;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $filterStatus = '';

    public $previewPdfData = null; // simpan PDF base64

    public function render()
    {
        $query = PengajuanSurat::with('profil');

        // 🔍 Pencarian berdasarkan profil
        if ($this->search) {
            $query->whereHas('profil', function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->orWhere('marga', 'like', "%{$this->search}%");
            });
        }

        // 🧩 Filter status (opsional)
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // 🔽 Urutan data terbaru dulu
        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.data-surat-oap', [
            'pengajuan' => $pengajuan
        ]);

    }

    public function setStatus($id, $status)
    {
        $item = PengajuanSurat::findOrFail($id);
        $item->update(['status' => $status]);
        session()->flash('success', "Status surat berhasil diperbarui ke '$status'.");
    }


    public function exportExcel()
    {
        $fileName = 'data_pengajuan_surat_oap_' . now()->format('Ymd_His') . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'Marga');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Tanggal Pengajuan');

        // Ambil data sesuai filter/search
        $query = PengajuanSurat::with('profil');

        if ($this->search) {
            $query->whereHas('profil', function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                  ->orWhere('nik', 'like', "%{$this->search}%")
                  ->orWhere('marga', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $data = $query->orderBy('created_at', 'desc')->get();

        $row = 2;
        $no = 1;
        foreach ($data as $item) {
            $profil = $item->profil;
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $profil->nama_lengkap ?? '-');
            $sheet->setCellValue("C{$row}", $profil->nik ?? '-');
            $sheet->setCellValue("D{$row}", $profil->marga ?? '-');
            $sheet->setCellValue("E{$row}", $item->status);
            $sheet->setCellValue("F{$row}", $item->created_at->format('Y-m-d H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="data_pengajuan_surat_oap_' . now()->format('Ymd_His') . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ]);
    }


    public function unduhSurat($id)
    {
        $surat = PengajuanSurat::findOrFail($id);

        if (!$surat->file_surat || !Storage::exists('public/' . $surat->file_surat)) {
            session()->flash('error', 'File surat tidak ditemukan.');
            return;
        }

        // Kirim response langsung sebagai download (stream)
        return response()->streamDownload(function () use ($surat) {
            echo Storage::get('public/' . $surat->file_surat);
        }, basename($surat->file_surat));
    }
    
}
