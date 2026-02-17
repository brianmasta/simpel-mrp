<div>
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Total Surat OAP</div>
                        <div class="fs-3">{{ $totalSurat }}</div>
                    </div>
                    <i class="cil-description fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Surat Terbit</div>
                        <div class="fs-3">{{ $suratTerbit }}</div>
                    </div>
                    <i class="cil-check-circle fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card bg-warning text-dark">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Perlu Perbaikan</div>
                        <div class="fs-3">{{ $suratPerbaikan }}</div>
                    </div>
                    <i class="cil-loop fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-danger">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-6 fw-semibold">Ditolak</div>
                        <div class="fs-3">{{ $suratDitolak }}</div>
                    </div>
                    <i class="cil-x-circle fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted">Total Marga OAP</div>
                        <div class="fs-2 fw-semibold text-info">
                            {{ $totalMarga }}
                        </div>
                    </div>
                    <i class="cil-people fs-1 text-info opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-start border-secondary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="text-muted">Total Akun SIMPEL-MRP</div>
                        <div class="fs-2 fw-semibold">{{ $totalAkun }}</div>
                    </div>
                    <i class="cil-user fs-1 text-secondary opacity-75"></i>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-danger">
                        Admin: {{ $adminCount }}
                    </span>
                    <span class="badge bg-warning text-dark">
                        Petugas: {{ $petugasCount }}
                    </span>
                    <span class="badge bg-info">
                        Pengguna: {{ $penggunaCount }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="cil-list me-2"></i> Rekapan Detail Surat OAP
        </h5>

        <button wire:click="exportPdf"
            class="btn btn-sm btn-danger">
            <i class="cil-file-pdf me-1"></i> Export PDF
        </button>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Pemohon</th>
                    <th>NIK</th>
                    <th>Kabupaten</th>
                    <th>No Surat</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratList as $index => $surat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $surat->profil->nama_lengkap ?? '-' }}</td>
                        <td>{{ $surat->user->profil->nik ?? '-' }}</td>
                        <td>{{ $surat->user->profil->kabupaten->nama ?? '-' }}</td>
                        <td>{{ $surat->nomor_surat ?? '-' }}</td>
                        <td>{{ $surat->alasan ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $surat->status == 'terbit' ? 'success' :
                                ($surat->status == 'proses' ? 'warning text-dark' : 'danger')
                            }}">
                                {{ ucfirst($surat->status) }}
                            </span>
                        </td>
                        <td>{{ $surat->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada data surat
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="cil-list me-2"></i> Rekapan Data Marga Terdaftar
        </h5>

        <button wire:click="exportPdfMarga"
            class="btn btn-sm btn-danger">
            <i class="cil-file-pdf me-1"></i> Export PDF
        </button>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="8%">No</th>
                    <th>Nama Marga</th>
                    <th>Wilayah Adat</th>
                    <th>Suku</th>
                    <th width="25%">Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse($margaList as $i => $marga)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ strtoupper($marga->marga) }}</td>
                    <td>{{ strtoupper($marga->wilayah_adat) }}</td>
                    <td>{{ strtoupper($marga->suku) }}</td>
                    <td class="text-center">
                        {{ optional($marga->created_at)->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada data marga
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-4 mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="cil-user me-2"></i> Rekapan Data Akun SIMPEL-MRP
        </h5>

        <button wire:click="exportPdfAkun" class="btn btn-sm btn-danger">
            <i class="cil-file-pdf me-1"></i> Export PDF
        </button>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="12%">Role</th>
                    <th>Kabupaten</th>
                    <th width="15%">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($akunList as $i => $user)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ 
                            $user->role === 'admin' ? 'danger' :
                            ($user->role === 'petugas' ? 'warning text-dark' : 'info')
                        }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        {{ $user->profil->kabupaten->nama ?? '-' }}
                    </td>
                    <td class="text-center">
                        {{ optional($user->created_at)->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Belum ada data akun
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
