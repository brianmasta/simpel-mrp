<?php

namespace App\Exports;

use App\Models\PengajuanSurat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengajuanSuratExport implements FromCollection, WithHeadings, WithMapping
{
    public $search;
    public $filterStatus;

    public function __construct($search = null, $filterStatus = null)
    {
        $this->search = $search;
        $this->filterStatus = $filterStatus;
    }

    public function collection()
    {
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

        return $query->get();
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->profil->nama_lengkap ?? '-',
            $item->profil->nik ?? '-',
            $item->profil->marga ?? '-',
            ucfirst($item->status ?? '-'),
            $item->nomor_surat ?? '-',
            $item->created_at ? $item->created_at->format('d/m/Y') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'NIK',
            'Marga',
            'Status',
            'Nomor Surat',
            'Tanggal Pengajuan',
        ];
    }
}
