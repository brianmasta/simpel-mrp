<div>
    {{-- =======================
     | KARTU STATISTIK
     ======================= --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="fw-semibold">Total Surat OAP</div>
                    <div class="fs-3">{{ $totalSurat }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="fw-semibold">Surat Terbit</div>
                    <div class="fs-3">{{ $suratTerbit }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="fw-semibold">Perlu Perbaikan</div>
                    <div class="fs-3">{{ $suratPerbaikan }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="fw-semibold">Ditolak</div>
                    <div class="fs-3">{{ $suratDitolak }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- =======================
     | TABEL SURAT OAP
     ======================= --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <h5>Rekapan Surat OAP</h5>
            <button wire:click="exportPdf" class="btn btn-danger btn-sm">
                Export PDF
            </button>
        </div>

        <div class="card-body">

            {{-- Filter Surat --}}
            <div class="row mb-3 g-2">
                <div class="col-md-4">
                    <input type="text"
                           wire:model.live.debounce.500ms="searchSurat"
                           class="form-control"
                           placeholder="Cari nama / NIK">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="statusSurat" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="proses">Proses</option>
                        <option value="terbit">Terbit</option>
                        <option value="perlu_perbaikan">Perlu Perbaikan</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Kabupaten</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratList as $surat)
                            <tr>
                                <td>
                                    {{ ($suratList->currentPage()-1) * $suratList->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $surat->user->profil->nama_lengkap ?? '-' }}</td>
                                <td>{{ $surat->user->profil->nik ?? '-' }}</td>
                                <td>{{ $surat->user->profil->kabupaten->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $surat->status == 'terbit' ? 'success' :
                                        ($surat->status == 'perlu_perbaikan' ? 'warning' :
                                        ($surat->status == 'proses' ? 'info' : 'danger'))
                                    }}">
                                        {{ ucfirst(str_replace('_',' ',$surat->status)) }}
                                    </span>
                                </td>
                                <td>{{ $surat->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $suratList->links() }}
        </div>
    </div>

    <div class="row g-3 mb-4">
    @php
        $wilayahList = [
            'Mamta/Tabi',
            'Saireri',
            'Domberai',
            'Bomberai',
            'Meepago',
            'La Pago',
            'Ha Anim',
        ];
    @endphp

    @foreach ($wilayahList as $wilayah)
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="text-muted small">
                        Wilayah Adat
                    </div>
                    <div class="fw-semibold">
                        {{ $wilayah }}
                    </div>
                    <div class="fs-3 text-info mt-1">
                        {{ $rekapanWilayahAdat[$wilayah] ?? 0 }}
                    </div>
                    <div class="small text-muted">
                        Marga Terdaftar
                    </div>
                </div>
            </div>
        </div>
    @endforeach

        {{-- TOTAL KESELURUHAN MARGA --}}
    <div class="col-md-3 col-sm-6">
        <div class="card border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="text-muted small">
                    Total Keseluruhan
                </div>

                <div class="fw-semibold">
                    Marga OAP Terdaftar
                </div>

                <div class="fs-2 text-success mt-1">
                    {{ $totalMarga }}
                </div>

                <div class="small text-muted">
                    Seluruh Wilayah Adat Papua
                </div>
            </div>
        </div>
    </div>
</div>


    {{-- =======================
     | TABEL MARGA
     ======================= --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <h5>Rekapan Data Marga</h5>
            <button wire:click="exportPdfMarga" class="btn btn-danger btn-sm">
                Export PDF
            </button>
        </div>

        <div class="card-body">

            {{-- Filter Marga --}}
            <div class="row mb-3 g-2">
                <div class="col-md-5">
                    <input type="text"
                           wire:model.live.debounce.500ms="searchMarga"
                           class="form-control"
                           placeholder="Cari marga / suku">
                </div>
                <div class="col-md-4">
                    <select wire:model.live="wilayahAdat" class="form-select">
                        <option value="">-- Semua Wilayah --</option>
                        <option value="Mamta/Tabi">Mamta/Tabi</option>
                        <option value="Saireri">Saireri</option>
                        <option value="Domberai">Domberai</option>
                        <option value="Bomberai">Bomberai</option>
                        <option value="Meepago">Meepago</option>
                        <option value="La Pago">La Pago</option>
                        <option value="Ha Anim">Ha Anim</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Marga</th>
                        <th>Wilayah Adat</th>
                        <th>Suku</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($margaList as $marga)
                        <tr>
                            <td>
                                {{ ($margaList->currentPage()-1) * $margaList->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ strtoupper($marga->marga) }}</td>
                            <td>{{ strtoupper($marga->wilayah_adat) }}</td>
                            <td>{{ strtoupper($marga->suku) }}</td>
                            <td>{{ $marga->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $margaList->links() }}
        </div>
    </div>

    <div class="row g-3 mb-4">

    {{-- ADMIN --}}
    <div class="col-md-3 col-sm-6">
        <div class="card border-start border-danger border-4 h-100">
            <div class="card-body">
                <div class="text-muted small">Admin</div>
                <div class="fs-3 fw-semibold text-danger">
                    {{ $adminCount }}
                </div>
                <div class="small text-muted">
                    Pengelola Sistem
                </div>
            </div>
        </div>
    </div>

    {{-- PETUGAS --}}
    <div class="col-md-3 col-sm-6">
        <div class="card border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="text-muted small">Petugas</div>
                <div class="fs-3 fw-semibold text-warning">
                    {{ $petugasCount }}
                </div>
                <div class="small text-muted">
                    Verifikator & Operator
                </div>
            </div>
        </div>
    </div>

    {{-- PENGGUNA --}}
    <div class="col-md-3 col-sm-6">
        <div class="card border-start border-info border-4 h-100">
            <div class="card-body">
                <div class="text-muted small">Pengguna</div>
                <div class="fs-3 fw-semibold text-info">
                    {{ $penggunaCount }}
                </div>
                <div class="small text-muted">
                    Masyarakat Umum
                </div>
            </div>
        </div>
    </div>
    {{-- TOTAL AKUN --}}
    <div class="col-md-3 col-sm-6">
        <div class="card border-start border-secondary border-4 h-100">
            <div class="card-body">
                <div class="text-muted small">
                    Total Keseluruhan
                </div>

                <div class="fw-semibold">
                    Akun SIMPEL-MRP
                </div>

                <div class="fs-2 text-secondary mt-1">
                    {{ $totalAkun }}
                </div>

                <div class="small text-muted">
                    Seluruh pengguna sistem
                </div>
            </div>
        </div>
    </div>
</div>



    {{-- =======================
     | TABEL AKUN
     ======================= --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
            <h5>Rekapan Data Akun</h5>
            <button wire:click="exportPdfAkun" class="btn btn-danger btn-sm">
                Export PDF
            </button>
        </div>

        <div class="card-body">

            {{-- Filter Akun --}}
            <div class="row mb-3 g-2">
                <div class="col-md-5">
                    <input type="text"
                           wire:model.live.debounce.500ms="searchAkun"
                           class="form-control"
                           placeholder="Cari nama / email">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="roleAkun" class="form-select">
                        <option value="">-- Semua Role --</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="pengguna">Pengguna</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Kabupaten</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($akunList as $user)
                        <tr>
                            <td>
                                {{ ($akunList->currentPage()-1) * $akunList->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $user->role == 'admin' ? 'danger' :
                                    ($user->role == 'petugas' ? 'warning' : 'info')
                                }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->profil->kabupaten->nama ?? '-' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $akunList->links() }}
        </div>
    </div>
</div>
