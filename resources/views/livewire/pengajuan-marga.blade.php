<div>
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

    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Pengajuan Marga OAP</strong></div>
        <div class="card-body">

            {{-- Alert sukses --}}
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" wire:model.defer="nama_lengkap">
                <small class="text-muted">Sesuai KTP atau akta kelahiran.</small>
                @error('nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>NIK</label>
                <input type="number" class="form-control" wire:model.defer="nik">
                <small class="text-muted">Nomor Induk Kependudukan (16 digit).</small>
                @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Wilayah Adat</label>
                <select class="form-control" wire:model.defer="wilayah_adat">
                    <option value="">-- Pilih Wilayah Adat --</option>
                    <option value="Mamta/Tabi">Mamta/Tabi</option>
                    <option value="Saireri">Saireri</option>
                    <option value="Domberai">Domberai</option>
                    <option value="Bomberai">Bomberai</option>
                    <option value="Meepago">Meepago</option>
                    <option value="La Pago">La Pago</option>
                    <option value="Ha Anim">Ha Anim</option>
                </select>
                <small class="text-muted">Pilih wilayah adat sesuai garis keturunan.</small>
                @error('wilayah_adat') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Suku</label>
                <input type="text" class="form-control" wire:model.defer="suku">
                <small class="text-muted">Nama suku tempat marga ini berasal.</small>
                @error('suku') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Marga</label>
                <input type="text" class="form-control" wire:model.defer="marga">
                <small class="text-muted">Nama marga yang ingin didaftarkan.</small>
                @error('marga') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label>Alasan Pengajuan</label>
                <textarea class="form-control" wire:model.defer="alasan"></textarea>
                <small class="text-muted">Jelaskan hubungan garis keturunan atau alasan administrasi.</small>
            </div>

            <div class="mb-3">
                <label>Upload Berkas Pendukung (PDF/JPG/PNG)</label>
                <input type="file" class="form-control" wire:model="berkas">
                <small class="text-muted">Contoh: KK, akta kelahiran, atau surat keterangan adat.</small>
                <div wire:loading wire:target="berkas" class="text-info">Mengunggah...</div>
                @error('berkas') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            @if ($pesanDuplikat)
                <div class="text-danger mb-2">{{ $pesanDuplikat }}</div>
            @endif
            <div class="d-grid gap-2">
                <button 
                    class="btn btn-primary" 
                    wire:click="save"
                    wire:loading.attr="disabled" 
                    wire:target="save"
                >
                    <span wire:loading.remove wire:target="save">Kirim Pengajuan</span>
                    <span wire:loading wire:target="save">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Mengirim...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
