<div>
    <div class="card mb-4">
        <div class="card-header">
            <strong>Verifikasi Pengajuan Marga OAP</strong>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="mb-2">
                <button wire:click="exportExcel" 
                        wire:loading.attr="disabled" 
                        wire:target="exportExcel" 
                        class="btn btn-success btn-sm">
                    <span wire:loading wire:target="exportExcel" class="spinner-border spinner-border-sm me-1"></span>
                    Export Excel
                </button>
            </div>
            <input wire:model.live="search" type="text" class="form-control form-control-sm" placeholder="Cari nama/NIK...">

            
            <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Marga</th>
                        <th>Suku</th>
                        <th>Wilayah Adat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->marga }}</td>
                            <td>{{ $item->suku }}</td>
                            <td>{{ $item->wilayah_adat }}</td>
                            <td>
                                @if ($item->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif ($item->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->status == 'pending')
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-success btn-sm" wire:click="setuju({{ $item->id }})">
                                            Setujui
                                        </button>
                                        <button class="btn btn-danger btn-sm" wire:click="tolak({{ $item->id }})">
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <em class="text-muted">Selesai</em>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                        {{ $pengajuan->links() }}
            </div>

        </div>
    </div>
</div>