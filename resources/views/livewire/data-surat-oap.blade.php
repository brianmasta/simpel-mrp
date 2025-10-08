<div>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Pengajuan Surat OAP</h5>

            <div class="d-flex gap-2">
                <input wire:model.live="search" type="text" class="form-control form-control-sm" placeholder="Cari nama/NIK...">
                <select wire:model="filterStatus" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="diproses">Diproses</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="card-body">
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

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Surat</th>
                        <th>Nama Lengkap</th>
                        {{-- <th>NIK</th> --}}
                        <th>Asal Daerah</th>
                        <th>Tujuan Surat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
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
                            <td>{{ $item->profil->kabupaten->nama }}</td>
                            <td>{{ $item->alasan }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status == 'disetujui' ? 'success' : ($item->status == 'diproses' ? 'warning' : ($item->status == 'ditolak' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>{{ $item->updated_at->format('d/m/Y') }}</td>
                            <td>
                                @if ($item->file_surat)
                                    <a href="{{ Storage::url($item->file_surat) }}" target="_blank" class="btn btn-sm btn-warning">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-muted">Belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">Belum ada pengajuan surat</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $pengajuan->links() }}
        </div>
    </div>

    <!-- Modal PDF Preview -->
    <div class="modal fade" id="pdfModal" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Preview Surat OAP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

</div>

@script
    <script>
        window.addEventListener('show-pdf-modal', event => {
            var myModal = new bootstrap.Modal(document.getElementById('pdfModal'));
            myModal.show();
        });
    </script>
@endscript
