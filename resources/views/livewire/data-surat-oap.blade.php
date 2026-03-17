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
                                                    <button
                                        wire:click="editData({{ $item->id }})"
                                        class="btn btn-warning btn-sm ms-1">
                                        <i class="bi bi-pencil"></i> Edit
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0 shadow">

      <!-- HEADER -->
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title fw-semibold">
            <i class="bi bi-file-earmark-text me-2"></i>
            Detail Pengajuan Surat OAP
        </h5>
        <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body bg-light">
        @if($selectedData)
        <div class="row g-3">

          <!-- KIRI: INFORMASI -->
          <div class="col-md-6">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white fw-bold">
                Informasi Pemohon
              </div>

              <div class="card-body p-2">
                <table class="table table-sm mb-0">
                  <tr><th width="40%">No Surat</th><td>{{ $selectedData->nomor_surat }}</td></tr>
                  <tr><th>Nama</th><td>{{ $selectedData->profil->nama_lengkap ?? '-' }}</td></tr>
                  <tr><th>NIK</th><td>{{ $selectedData->profil->nik ?? '-' }}</td></tr>
                  <tr><th>Kabupaten</th><td>{{ $selectedData->profil->kabupaten->nama ?? '-' }}</td></tr>
                  <tr><th>Tujuan</th><td>{{ $selectedData->alasan }}</td></tr>
                  <tr>
                    <th>Status</th>
                    <td>
                      <span class="badge bg-info">
                        {{ ucfirst($selectedData->status) }}
                      </span>
                    </td>
                  </tr>
                  <tr><th>Tanggal</th><td>{{ $selectedData->created_at->format('d/m/Y') }}</td></tr>
                </table>
              </div>
            </div>
          </div>

          <!-- KANAN -->
          <div class="col-md-6">

            <!-- BERKAS -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-header bg-white fw-bold">
                Berkas Pendukung
              </div>

              <div class="card-body">
                <div class="d-grid gap-2">

                  @if($selectedData->foto)
                  <a href="{{ route('berkas.akses', [$selectedData->id, 'foto']) }}" target="_blank"
                     class="btn btn-outline-primary btn-sm text-start">
                    <i class="bi bi-image me-2"></i> Foto 4x6
                  </a>
                  @endif

                  @if($selectedData->ktp)
                  <a href="{{ route('berkas.akses', [$selectedData->id, 'ktp']) }}" target="_blank"
                     class="btn btn-outline-primary btn-sm text-start">
                    <i class="bi bi-credit-card me-2"></i> KTP
                  </a>
                  @endif

                  @if($selectedData->kk)
                  <a href="{{ route('berkas.akses', [$selectedData->id, 'kk']) }}" target="_blank"
                     class="btn btn-outline-primary btn-sm text-start">
                    <i class="bi bi-people me-2"></i> Kartu Keluarga
                  </a>
                  @endif

                  @if($selectedData->akte)
                  <a href="{{ route('berkas.akses', [$selectedData->id, 'akte']) }}" target="_blank"
                     class="btn btn-outline-primary btn-sm text-start">
                    <i class="bi bi-file-earmark-text me-2"></i> Akte
                  </a>
                  @endif

                </div>
              </div>
            </div>

            <!-- FORM ADMIN -->
            @if(auth()->user()->role === 'admin')
            <div class="card shadow-sm border-0">
              <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-pencil-square me-2"></i> Perbaikan Data
              </div>

              <div class="card-body">

                <form wire:submit.prevent="perbaikiDanTerbitkan">

                  <div class="row g-2">

                    <div class="col-md-6">
                      <label class="form-label">Nama</label>
                      <input type="text" wire:model="editNama" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">NIK</label>
                      <input type="text" wire:model="editNik" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Nama Ayah</label>
                      <input type="text" wire:model="editNamaAyah" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Nama Ibu</label>
                      <input type="text" wire:model="editNamaIbu" class="form-control form-control-sm">
                    </div>

                    <div class="col-12">
                      <label class="form-label">Tujuan Surat</label>
                      <textarea wire:model="editAlasan" class="form-control form-control-sm"></textarea>
                    </div>

                    <!-- FOTO -->
                    <div class="col-12">
                      <label class="form-label">Ganti Foto</label>
                      <input type="file" wire:model="fotoBaru" class="form-control form-control-sm">
                    </div>

                    <!-- LOADING -->
                    <div wire:loading wire:target="fotoBaru"
                         class="alert alert-info py-1 px-2">
                      <span class="spinner-border spinner-border-sm me-2"></span>
                      Uploading...
                    </div>

                    <!-- PREVIEW -->
                    @if ($fotoBaru)
                      <div class="col-12 text-center">
                        <img src="{{ $fotoBaru->temporaryUrl() }}"
                             class="img-thumbnail"
                             style="max-height:120px">
                      </div>
                    @endif

                    <!-- BUTTON -->
                    <div class="col-12">
                      <button type="submit"
                              wire:loading.attr="disabled"
                              class="btn btn-danger btn-sm w-100 mt-2">
                        <span wire:loading class="spinner-border spinner-border-sm me-1"></span>
                        Simpan & Terbitkan
                      </button>
                    </div>

                  </div>

                </form>

              </div>
            </div>
            @endif

          </div>

        </div>
        @else
          <div class="text-center text-muted py-5">
            <i class="bi bi-file-earmark-x fs-1"></i>
            <p class="mt-2">Data tidak tersedia</p>
          </div>
        @endif
      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-coreui-dismiss="modal">
          Tutup
        </button>
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
