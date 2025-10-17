<div>
    <div class="card mb-4">
    <div class="card-header bg-dark text-white"><strong>Pengajuan Marga OAP</strong></div>
        <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" wire:model.defer="nama_lengkap">
                    @error('nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <input type="text" class="form-control" wire:model.defer="nik">
                    @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>Wilayah Adat</label>
                    <select class="form-control" wire:model.defer="wilayah_adat">
                        <option value="">-- Pilih Wilayah Adat --</option>
                        <option value="Saireri">Saireri</option>
                        <option value="Domberai">Domberai</option>
                        <option value="Bomberai">Bomberai</option>
                        <option value="Meepago">Meepago</option>
                        <option value="La Pago">La Pago</option>
                        <option value="Ha Anim">Ha Anim</option>
                    </select>
                    @error('wilayah_adat') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>Suku</label>
                    <input type="text" class="form-control" wire:model.defer="suku">
                    @error('suku') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>Marga</label>
                    <input type="text" class="form-control" wire:model.defer="marga">
                    @error('marga') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label>Alasan Pengajuan</label>
                    <textarea class="form-control" wire:model.defer="alasan"></textarea>
                </div>

                <div class="mb-3">
                    <label>Upload Berkas Pendukung (PDF/JPG/PNG)</label>
                    <input type="file" class="form-control" wire:model="berkas">
                    <div wire:loading wire:target="berkas" class="text-info">Mengunggah...</div>
                    @error('berkas') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button class="btn btn-primary" wire:click="save">Kirim Pengajuan</button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Riwayat Pengajuan Marga OAP</strong></div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Marga</th>
                        <th>Daerah/Suku</th>
                        <th>Wilayah Adat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat_pengajuan as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->marga }}</td>
                            <td>{{ $item->suku }}</td>
                            <td>{{ $item->wilayah_adat }}</td>
                            <td>
                                @if ($item->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif ($item->status == 'disetujui')
                                    <span class="badge bg-success">Diterima</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pengajuan marga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

</div>

