<div>
    <style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    opacity: 0.4;
}

.timeline-item.active {
    opacity: 1;
}

.timeline-point {
    position: absolute;
    left: -2px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 30px;
}
    </style>
                <button class="btn btn-primary mb-3"
                    data-coreui-toggle="modal"
                    data-coreui-target="#modalPengajuanMarga">
                <i class="cil-plus"></i> Ajukan Marga OAP
            </button>
@foreach ($riwayat_pengajuan as $item)
    <div class="card mb-4">

        <!-- Header -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Marga: {{ $item->marga }}</strong><br>
                <small class="text-muted">
                    Suku: {{ $item->suku }} |
                    Wilayah Adat: {{ $item->wilayah_adat }}
                </small>
            </div>

            <!-- Badge Status -->
            @if($item->status == 'diajukan')
                <span class="badge bg-secondary">Diajukan</span>
            @elseif($item->status == 'pending')
                <span class="badge bg-warning">Menunggu Verifikasi</span>
            @elseif($item->status == 'disetujui')
                <span class="badge bg-success">Disetujui</span>
            @elseif($item->status == 'ditolak')
                <span class="badge bg-danger">Ditolak</span>
            @else
                <span class="badge bg-danger">proses</span>
            @endif
        </div>

        <!-- Body -->
        <div class="card-body">

            <div class="timeline">

                <!-- Tahap 1 -->
                <div class="timeline-item active">
                    <span class="timeline-point bg-primary"></span>
                    <div class="timeline-content">
                        <strong>Diajukan Pemohon</strong><br>
                        <small class="text-muted">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </small>
                    </div>
                </div>

                <!-- Tahap 2 -->
                <div class="timeline-item
                    {{ in_array($item->status, ['menunggu_verifikasi','disetujui','ditolak']) ? 'active' : '' }}">
                    <span class="timeline-point bg-warning"></span>
                    <div class="timeline-content">
                        <strong>Menunggu Verifikasi Petugas</strong><br>

                        @if($item->waktu_verifikasi)
                            <small class="text-muted">
                                {{ $item->waktu_verifikasi->format('d M Y H:i') }}
                            </small>
                        @else
                            <small class="text-muted">Sedang diproses</small>
                        @endif
                    </div>
                </div>

                <!-- Tahap 3 -->
                <div class="timeline-item
                    {{ in_array($item->status, ['disetujui','ditolak']) ? 'active' : '' }}">
                    <span class="timeline-point
                        {{ $item->status == 'disetujui' ? 'bg-success' : 'bg-danger' }}"></span>

                    <div class="timeline-content">
                        <strong>
                            {{ $item->status == 'disetujui' ? 'Disetujui MRP' : 'Ditolak' }}
                        </strong><br>

                        @if(in_array($item->status, ['disetujui','ditolak']))
                            <small class="text-muted">
                                {{ $item->updated_at->format('d M Y H:i') }}
                            </small>
                        @else
                            <small class="text-muted">Belum ada keputusan</small>
                        @endif
                    </div>
                </div>

                <!-- Catatan Penolakan -->
                @if($item->status == 'ditolak')
                    <div class="alert alert-danger mt-3">
                        <strong>Catatan Petugas:</strong><br>
                        {{ $item->catatan_petugas ?? 'Tidak ada catatan.' }}
                    </div>
                @endif

            </div>

        </div>

    </div>
@endforeach

<div wire:ignore.self
     class="modal fade"
     id="modalPengajuanMarga"
     tabindex="-1"
     aria-labelledby="modalPengajuanMargaLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalPengajuanMargaLabel">
                    Pengajuan Marga OAP
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-coreui-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">

                <div class="alert alert-info">
                    <strong>Keterangan:</strong><br>
                    Pengajuan marga digunakan untuk verifikasi dan penetapan marga
                    Orang Asli Papua (OAP) berdasarkan garis keturunan sesuai ketentuan
                    Majelis Rakyat Papua (MRP).
                </div>

                {{-- Alert sukses --}}
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text"
                           class="form-control"
                           wire:model.defer="nama_lengkap">
                    <small class="text-muted">Sesuai KTP atau akta kelahiran.</small>
                    @error('nama_lengkap') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">NIK</label>
                    <input type="number"
                           class="form-control"
                           wire:model.defer="nik">
                    <small class="text-muted">Nomor Induk Kependudukan (16 digit).</small>
                    @error('nik') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Wilayah Adat</label>
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
                    @error('wilayah_adat') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Suku</label>
                    <input type="text"
                           class="form-control"
                           wire:model.defer="suku">
                    @error('suku') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Marga</label>
                    <input type="text"
                           class="form-control"
                           wire:model.defer="marga">
                    @error('marga') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Alasan Pengajuan</label>
                    <textarea class="form-control"
                              rows="3"
                              wire:model.defer="alasan"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Berkas Pendukung</label>
                    <input type="file"
                           class="form-control"
                           wire:model="berkas">
                    <small class="text-muted">
                        KK, Akta Kelahiran, atau Surat Keterangan Adat.
                    </small>

                    <div wire:loading
                         wire:target="berkas"
                         class="text-info mt-1">
                        Mengunggah berkas...
                    </div>

                    @error('berkas') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                @if ($pesanDuplikat)
                    <div class="alert alert-danger">
                        {{ $pesanDuplikat }}
                    </div>
                @endif

            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-coreui-dismiss="modal">
                    Batal
                </button>

                <button class="btn btn-primary"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        Kirim Pengajuan
                    </span>
                    <span wire:loading wire:target="save">
                        <span class="spinner-border spinner-border-sm"></span>
                        Mengirim...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    window.addEventListener('pengajuanBerhasil', () => {
        const modalEl = document.getElementById('modalPengajuanMarga');
        const modal = coreui.Modal.getInstance(modalEl);

        // 👉 pindahkan fokus ke body / tombol lain
        document.body.focus();

        // 👉 baru tutup modal
        modal.hide();
    });
</script>
</div>
