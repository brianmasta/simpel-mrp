<div class="container-fluid">

    {{-- JUDUL --}}
    <div class="mb-4">
        <h4 class="fw-bold">
            <i class="cil-user-check me-1"></i> Verifikasi Berkas Surat OAP
        </h4>
        <p class="text-muted mb-0">
            Periksa dan verifikasi kelengkapan berkas pengajuan Surat OAP
        </p>
    </div>

    <div class="row">

        {{-- ================== DAFTAR PENGAJUAN ================== --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    <i class="cil-folder-open me-1"></i> Daftar Pengajuan
                </div>

                <div class="card-body p-2" style="max-height: 70vh; overflow-y:auto">

                    @forelse ($pengajuans as $item)
                        @php
                            $statusMap = [
                                'menunggu_verifikasi_berkas' => ['warning', 'Menunggu Verifikasi'],
                                'perlu_perbaikan'            => ['danger', 'Perlu Perbaikan'],
                                'berkas_lengkap'             => ['info', 'Berkas Lengkap'],
                                'draft_surat'                => ['primary', 'Draft Surat'],
                                'terbit'                     => ['success', 'Terbit'],
                            ];
                            [$color, $label] = $statusMap[$item->status]
                                ?? ['secondary', strtoupper($item->status)];
                        @endphp

                        <div
                            wire:click="pilihPengajuan({{ $item->id }})"
                            class="border rounded p-3 mb-2 cursor-pointer
                                {{ optional($selectedPengajuan)->id === $item->id ? 'border-primary bg-light' : '' }}"
                            style="cursor:pointer"
                        >
                            <div class="fw-semibold">
                                {{ $item->user->name ?? 'User' }}
                            </div>

                            <div class="small text-muted">
                                {{ $item->created_at->format('d M Y') }}
                            </div>

                            <span class="badge bg-{{ $color }} mt-2">
                                {{ $label }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">
                            Tidak ada pengajuan
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- ================== DETAIL & VERIFIKASI ================== --}}
        <div class="col-md-8">
            <div class="card shadow-sm">

                <div class="card-header fw-semibold">
                    <i class="cil-clipboard me-1"></i> Detail Verifikasi
                </div>

                <div class="card-body">

                    @if ($selectedPengajuan)

                        {{-- INFO PEMOHON --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama Pemohon</strong><br>
                                {{ $selectedPengajuan->user->name ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Status Pengajuan</strong><br>
                                <span class="text-capitalize">
                                    {{ str_replace('_',' ', $selectedPengajuan->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- TABEL VERIFIKASI --}}
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Dokumen</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                        <th width="90">File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedPengajuan->verifikasi as $v)
                                        @php
                                            $file = $selectedPengajuan?->{$v->dokumen};
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold text-uppercase">
                                                {{ $v->dokumen }}
                                            </td>

                                            <td>
                                                <select
                                                    wire:model="verifikasi.{{ $v->id }}.status"
                                                    class="form-select form-select-sm"
                                                    @disabled($selectedPengajuan->status === 'terbit')
                                                >
                                                    <option value="menunggu">Menunggu</option>
                                                    <option value="valid">Valid</option>
                                                    <option value="perlu_perbaikan">Perlu Perbaikan</option>
                                                </select>
                                            </td>

                                            <td>
                                                <input
                                                    type="text"
                                                    wire:model="verifikasi.{{ $v->id }}.catatan"
                                                    class="form-control form-control-sm"
                                                    placeholder="Catatan petugas"
                                                    @disabled($selectedPengajuan->status === 'terbit')
                                                >
                                            </td>

                                            <td class="text-center">
                                                @php 
                                                $file = $selectedPengajuan?->{$v->dokumen}; 
                                                @endphp 
                                                @if($file) 
                                                <a href="{{ route('view.private', [ 'folder' => $v->dokumen, 'filename' => basename($file) ]) }}" target="_blank" class="btn btn-sm btn-outline-primary"> <i class="bi bi-eye me-1"></i> Lihat </a> 
                                                @else <span class="text-muted">Tidak ada</span> 
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- AKSI --}}
                        <div class="d-flex flex-wrap gap-2 mt-3">

                            <button
                                wire:click="simpanVerifikasi"
                                class="btn btn-success"
                                @disabled($selectedPengajuan->status === 'terbit')
                            >
                                <i class="cil-save me-1"></i> Simpan Verifikasi
                            </button>

                            @if ($selectedPengajuan->status === 'berkas_lengkap')
                                <button
                                    wire:click="buatSuratPreview({{ $selectedPengajuan->id }})"
                                    class="btn btn-primary"
                                >
                                    <i class="cil-description me-1"></i> Buat Surat
                                </button>
                            @endif

                            @if ($selectedPengajuan->status === 'draft_surat')
                                <a
                                    href="{{ Storage::url($selectedPengajuan->file_surat) }}"
                                    target="_blank"
                                    class="btn btn-secondary"
                                >
                                    <i class="cil-magnifying-glass me-1"></i> Preview
                                </a>

                                <button
                                    wire:click="terbitkanSurat"
                                    class="btn btn-success"
                                    onclick="return confirm('Yakin surat sudah sesuai dan ingin diterbitkan?')"
                                >
                                    <i class="cil-check-circle me-1"></i> Terbitkan
                                </button>
                            @endif

                            <button
                                class="btn btn-danger"
                                wire:click="bukaModalTolak"
                                @disabled($selectedPengajuan->status === 'terbit')
                            >
                                <i class="cil-x-circle me-1"></i> Tolak Pengajuan
                            </button>

                        </div>

                    @else
                        <div class="text-center text-muted py-5">
                            <i class="cil-arrow-left me-1"></i>
                            Pilih pengajuan di sebelah kiri untuk mulai verifikasi
                        </div>
                    @endif

                </div>
            </div>
        </div>
        {{-- ================= MODAL TOLAK PENGAJUAN ================= --}}
<div
    class="modal fade @if($showTolakModal) show @endif"
    tabindex="-1"
    style="@if($showTolakModal) display:block; background:rgba(0,0,0,.5); @endif"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="cil-x-circle me-1"></i>
                    Tolak Pengajuan Surat OAP
                </h5>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    wire:click="$set('showTolakModal', false)">
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                <div class="mb-2">
                    <strong>Nama Pemohon:</strong><br>
                    {{ $selectedPengajuan->user->name ?? '-' }}
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Alasan Penolakan <span class="text-danger">*</span>
                    </label>

                    <textarea
                        wire:model.defer="alasanPenolakan"
                        rows="4"
                        class="form-control @error('alasanPenolakan') is-invalid @enderror"
                        placeholder="Tuliskan alasan penolakan secara jelas dan sopan..."
                    ></textarea>

                    @error('alasanPenolakan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="alert alert-warning small mb-0">
                    <i class="cil-warning me-1"></i>
                    Pengajuan yang ditolak <strong>tidak dapat diproses kembali</strong>.
                    Pastikan alasan penolakan sudah benar.
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    wire:click="$set('showTolakModal', false)">
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    wire:click="tolakPengajuan">
                    <i class="cil-x-circle me-1"></i> Tolak Pengajuan
                </button>
            </div>

        </div>
    </div>
</div>
    </div>
</div>
