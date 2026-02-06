<?php

namespace App\Livewire;

use App\Models\PengajuanSurat;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengajuanSuratExport;
use App\Mail\SuratOapMail;
use App\Services\FonnteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
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

    public $hapusId;
    public $hapusNama;
    public $detailSurat;
    public $previewPdfData = null; // simpan PDF base64
    public $selectedData;

    public function render()
    {
        $query = PengajuanSurat::with('profil');

        // 🔍 Pencarian berdasarkan profil
        if ($this->search) {
            $query->whereHas('profil', function ($q) {
                $q->where('nama_lengkap', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%");
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
        ])->layout('components.layouts.app');

    }

    public function lihatData($id)
    {
        $this->selectedData = PengajuanSurat::with(['profil.kabupaten'])->find($id);
        // dd($this->selectedData);
        $this->dispatch('show-lihat-data');
    }

    public function konfirmasiHapus($id)
    {
        $item = PengajuanSurat::with('profil')->find($id);
        $this->hapusId = $id;
        $this->hapusNama = $item?->profil?->nama_lengkap ?? 'Pengguna';
        $this->dispatch('show-hapus-modal'); // kirim event ke JS
    }

    public function hapusData()
    {
        $item = PengajuanSurat::find($this->hapusId);

        if (!$item) {
            session()->flash('error', 'Data tidak ditemukan.');
            return;
        }

        if ($item->file_surat && Storage::exists($item->file_surat)) {
            Storage::delete($item->file_surat);
        }

        $item->delete();
        $this->hapusId = null;
        $this->hapusNama = null;

        session()->flash('success', 'Data berhasil dihapus.');
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

    public function kirimEmail($id)
    {
        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);

        // ✅ email dari tabel users
        if (
            !$pengajuan->user ||
            !$pengajuan->user->email
        ) {
            session()->flash('error', 'Email pemohon belum tersedia.');
            return;
        }

        if (!$pengajuan->file_surat) {
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'File surat belum tersedia.'
            ]);
            return;
        }

        // ✅ path file sesuai Storage::url()
        $path = storage_path(
            'app/public/' . $pengajuan->file_surat
        );

        if (!file_exists($path)) {
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'File surat tidak ditemukan di server.'
            ]);
            return;
        }

        // ✅ kirim email
        Mail::to($pengajuan->user->email)
            ->send(new SuratOapMail($pengajuan, $path));

        // ✅ notifikasi UI
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Surat berhasil dikirim ke Email.'
        ]);
    }

    public function kirimWhatsapp($id)
    {
        $pengajuan = PengajuanSurat::with('user.profil')->findOrFail($id);

        if (
            !$pengajuan->user ||
            !$pengajuan->user->profil ||
            !$pengajuan->user->profil->no_hp
        ) {
            session()->flash('error', 'Nomor WhatsApp pemohon belum tersedia.');
            return;
        }

        if (!$pengajuan->file_surat) {
            session()->flash('error', 'File surat belum tersedia.');
            return;
        }

        // 🔥 LINK PDF ANTI CACHE (KONSISTEN)
        $linkSurat = url(
            Storage::url($pengajuan->file_surat)
        ) . '?v=' . time();

        $pesan =
            "📄 *SURAT OAP TELAH DITERBITKAN*\n\n" .
            "Yth. Bapak/Ibu *{$pengajuan->user->profil->nama}*,\n\n" .
            "Surat Keterangan Orang Asli Papua (OAP) Anda\n" .
            "telah *DITERBITKAN* dan dapat diunduh melalui tautan berikut:\n\n" .
            "🔗 {$linkSurat}\n\n" .
            "Hormat kami,\n" .
            "*SIMPEL-MRP*\n" .
            "Majelis Rakyat Papua Tengah";

        FonnteService::send(
            $pengajuan->user->profil->no_hp,
            $pesan
        );

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Surat berhasil dikirim ke WhatsApp.'
        ]);
        }
    
}
