<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Data Pengajuan Surat OAP</strong>
        </div>

        <div class="card-body">
            <div class="d-flex gap-2 mb-2">
                <input wire:model.live="search" type="text" class="form-control form-control-sm" placeholder="Cari nama/NIK...">
                <select wire:model="filterStatus" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="diproses">Diproses</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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
            <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Nama </th>
                        {{-- <th>NIK</th> --}}
                        <th>Asal Daerah</th>
                        <th>Tujuan Surat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Dokumen</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $index => $item)
                        <tr>
                            <td>{{ $pengajuan->firstItem() + $index }}</td>
                            <td>{{ $item->nomor_surat }}</td>
                            <td>{{ $item->profil->nama_lengkap }}</td>
                            {{-- <td>{{ $item->profil->nik }}</td> --}}
                            <td>{{ $item->profil->kabupaten->nama ?? '-' }}</td>
                            <td>{{ $item->alasan }}</td>
                            <td>
                                @if ($item->status === 'terbit')
                                    <span class="badge bg-success">Terbit</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $item->updated_at->format('d/m/Y') }}</td>
                            <td>
                                @if ($item->file_surat)
                                    <a href="{{ route('berkas.akses', [$item->id, 'surat']) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-warning">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">Belum tersedia</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <button
                                    wire:click="lihatData({{ $item->id }})"
                                    class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>

                                <button
                                    wire:click="kirimEmail({{ $item->id }})"
                                    class="btn btn-primary btn-sm ms-1">
                                    <i class="bi bi-envelope"></i> Email
                                </button>

                                <button
                                    wire:click="kirimWhatsapp({{ $item->id }})"
                                    class="btn btn-success btn-sm ms-1">
                                    <i class="bi bi-whatsapp"></i> WA
                                </button>

                                @if(auth()->user()->role === 'admin')
                                    <button
                                        class="btn btn-sm btn-danger ms-1"
                                        wire:click="konfirmasiHapus({{ $item->id }})">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">Belum ada pengajuan surat</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            {{ $pengajuan->links() }}
        </div>
    </div>

<!-- Modal Lihat Data -->
<!-- Modal Lihat Data -->
<div wire:ignore.self class="modal fade" id="lihatDataModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Detail Pengajuan Surat OAP</h5>
        <button type="button" class="btn-close btn-close-white"
                data-coreui-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        @if($selectedData)
            <div class="row">
                <!-- KIRI -->
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr><th>No Surat</th><td>{{ $selectedData->nomor_surat }}</td></tr>
                        <tr><th>Nama Lengkap</th><td>{{ $selectedData->profil->nama_lengkap ?? '-' }}</td></tr>
                        <tr><th>NIK</th><td>{{ $selectedData->profil->nik ?? '-' }}</td></tr>
                        <tr><th>Asal Kabupaten</th><td>{{ $selectedData->profil->kabupaten->nama ?? '-' }}</td></tr>
                        <tr><th>Tujuan Surat</th><td>{{ $selectedData->alasan }}</td></tr>
                        <tr><th>Status</th><td>{{ ucfirst($selectedData->status) }}</td></tr>
                        <tr><th>Tanggal</th><td>{{ $selectedData->created_at->format('d/m/Y') }}</td></tr>
                    </table>
                </div>

                <!-- KANAN -->
                <div class="col-md-6">
                    <h6 class="fw-bold mb-2">Berkas Pendukung</h6>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($selectedData->foto)
                            <a href="{{ route('berkas.akses', [$selectedData->id, 'foto']) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-person-bounding-box me-1"></i> Foto 4x6
                            </a>
                        @endif

                        @if($selectedData->ktp)
                            <a href="{{ route('berkas.akses', [$selectedData->id, 'ktp']) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-person-bounding-box me-1"></i> KTP
                            </a>
                        @endif

                        @if($selectedData->kk)
                            <a href="{{ route('berkas.akses', [$selectedData->id, 'kk']) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-person-bounding-box me-1"></i> KK
                            </a>
                        @endif

                        @if($selectedData->akte)
                            <a href="{{ route('berkas.akses', [$selectedData->id, 'akte']) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-person-bounding-box me-1"></i> Akte
                            </a>
                        @endif
                    </div>

@if(auth()->user()->role === 'admin')
    <hr>
    <h6 class="fw-bold text-danger">Perbaikan foto (Admin)</h6>

    <form wire:submit.prevent="perbaikiDanTerbitkan"
          enctype="multipart/form-data">

        {{-- INPUT FILE --}}
        <input type="file"
               wire:model="fotoBaru"
               accept="image/*"
               class="form-control form-control-sm mb-2">

        {{-- LOADING SAAT UPLOAD FOTO --}}
        <div wire:loading wire:target="fotoBaru"
             class="alert alert-info py-1 px-2 mb-2">
            <span class="spinner-border spinner-border-sm me-2"></span>
            Mengunggah foto…
        </div>

        {{-- PREVIEW FOTO --}}
        @if ($fotoBaru)
            <div class="mb-2">
                <small class="text-muted">Preview Foto Baru:</small><br>
                <img src="{{ $fotoBaru->temporaryUrl() }}"
                     class="img-thumbnail mt-1"
                     style="max-height:150px">
            </div>
        @endif

        {{-- VALIDATION ERROR --}}
        @error('fotoBaru')
            <small class="text-danger">{{ $message }}</small>
        @enderror

        {{-- BUTTON --}}
        <button type="submit"
                wire:loading.attr="disabled"
                wire:target="perbaikiDanTerbitkan,fotoBaru"
                class="btn btn-danger btn-sm">
            <span wire:loading
                  wire:target="perbaikiDanTerbitkan"
                  class="spinner-border spinner-border-sm me-1"></span>
            Perbaiki & Terbitkan Ulang
        </button>

        <small class="d-block mt-2 text-muted">
            ⚠ Nomor surat tetap, PDF diperbarui
        </small>
    </form>
@endif
                </div>
            </div>
        @else
            <p class="text-center text-muted">Data tidak tersedia</p>
        @endif
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
                data-coreui-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>

    <!-- Modal PDF Preview -->
    <div class="modal fade" id="pdfModal" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Preview Surat OAP</h5>
            <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            @if($previewPdfData)
                <iframe src="{{ $previewPdfData }}" frameborder="0" style="width:100%; height:80vh;"></iframe>
            @else
                <p class="text-center">PDF tidak tersedia</p>
            @endif
          </div>
        </div>
      </div>
    </div>

<!-- Modal Hapus -->
<div wire:ignore.self class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
            <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>Apakah Anda yakin ingin menghapus pengajuan atas nama:</p>
        <h6 class="fw-bold text-danger">{{ $hapusNama }}</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Batal</button>
        <button type="button" wire:click="hapusData" class="btn btn-danger" data-coreui-dismiss="modal">
          <i class="bi bi-trash"></i> Hapus
        </button>
      </div>
    </div>
  </div>
</div>

</div>

@script
<script>
document.addEventListener('livewire:initialized', () => {

    function showModal(id) {
        const el = document.getElementById(id);
        if (!el) return;

        let modal = coreui.Modal.getInstance(el);
        if (!modal) {
            modal = new coreui.Modal(el);
        }
        modal.show();
    }

    window.addEventListener('show-lihat-data', () => {
        showModal('lihatDataModal');
    });

    window.addEventListener('show-pdf-modal', () => {
        showModal('pdfModal');
    });

    window.addEventListener('show-hapus-modal', () => {
        showModal('hapusModal');
    });

});
</script>
@endscript
