<div>
    {{-- RIWAYAT PENGAJUAN SURAT --}}

<style>
/* sembunyikan preview desktop di HP */
@media (max-width: 768px) {
    .preview-desktop {
        display: none !important;
    }
}

/* sembunyikan preview mobile di desktop */
@media (min-width: 769px) {
    .preview-mobile {
        display: none !important;
    }
}

.timeline-item {
    position: relative;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 19px;
    top: 45px;
    width: 2px;
    height: calc(100% - 10px);
    background-color: #dee2e6;
}
</style>
<button 
    class="btn btn-primary mb-3"
    data-coreui-toggle="modal"
    data-coreui-target="#modalPengajuanSurat"
>
    <i class="cil-plus me-1"></i>
    Ajukan Surat OAP
</button>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <i class="cil-history me-2"></i>
        <strong>Riwayat Pengajuan Surat OAP</strong>
    </div>

    <div class="card-body">

        @forelse($riwayat as $index => $item)

        <div class="timeline-item mb-4">

            <div class="d-flex">

                {{-- ICON STATUS (COREUI ICON) --}}
                <div class="timeline-icon
                    bg-{{
                        in_array($item->status, ['terbit','valid']) ? 'success' :
                        ($item->status === 'perlu_perbaikan' ? 'secondary' :
                        (in_array($item->status, ['verifikasi','menunggu','menunggu_verifikasi']) ? 'warning' :
                        'danger'))
                    }}">
                    <i class="cil
                        {{
                            in_array($item->status, ['terbit','valid']) ? 'cil-check-circle' :
                            ($item->status === 'perlu_perbaikan' ? 'cil-warning' :
                            (in_array($item->status, ['verifikasi','menunggu','menunggu_verifikasi']) ? 'cil-clock' :
                            'cil-x-circle'))
                        }}">
                    </i>
                </div>

                {{-- CONTENT --}}
                <div class="timeline-content ms-3 w-100">

                    {{-- HEADER (klik untuk collapse) --}}
                    <div class="d-flex justify-content-between align-items-center"
                         data-coreui-toggle="collapse"
                         data-coreui-target="#detail{{ $index }}"
                         style="cursor:pointer;">

                        <div>
                            <h6 class="mb-0 fw-bold">
                                {{ $item->nomor_surat ?? 'Nomor Surat Belum Terbit' }}
                            </h6>
                            <small class="text-muted">
                                <i class="cil-calendar me-1"></i>
                                {{ $item->created_at?->format('d-m-Y H:i') }}
                            </small>
                        </div>

                        <span class="badge
                            bg-{{
                                in_array($item->status, ['terbit','valid']) ? 'success' :
                                ($item->status === 'perlu_perbaikan' ? 'secondary' :
                                (in_array($item->status, ['verifikasi','menunggu','menunggu_verifikasi']) ? 'warning' :
                                'danger'))
                            }}">
                            {{ strtoupper(str_replace('_',' ', $item->status)) }}
                        </span>
                    </div>

                    {{-- COLLAPSE DETAIL --}}
                    <div class="collapse mt-3" id="detail{{ $index }}">

                        <div class="card card-body bg-light border">

                            <p class="mb-2">
                                <i class="cil-description me-1"></i>
                                <strong>Alasan:</strong> {{ $item->alasan ?? '-' }}
                            </p>

                            {{-- DETAIL BERKAS --}}
                            <div class="row mb-3 small">
                                <div class="col-md-6">
                                    <i class="cil-image me-1 text-success"></i>
                                    Foto: {{ $item->foto ? 'Tersedia' : '-' }}
                                </div>
                                <div class="col-md-6">
                                    <i class="cil-file me-1 text-success"></i>
                                    KTP: {{ $item->ktp ? 'Tersedia' : '-' }}
                                </div>
                                <div class="col-md-6">
                                    <i class="cil-people me-1 text-success"></i>
                                    KK: {{ $item->kk ? 'Tersedia' : '-' }}
                                </div>
                                <div class="col-md-6">
                                    <i class="cil-file me-1 text-success"></i>
                                    Akte: {{ $item->akte ? 'Tersedia' : '-' }}
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2">

                                @if($item->status === 'perlu_perbaikan')
                                    <a href="{{ route('perbaikan-berkas', $item->id) }}"
                                       class="btn btn-sm btn-danger">
                                        <i class="cil-wrench me-1"></i>
                                        Perbaiki Berkas
                                    </a>
                                @endif

                                @if($item->file_surat && Storage::disk('public')->exists($item->file_surat))
                                    <a href="{{ Storage::url($item->file_surat) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-success">
                                        <i class="cil-cloud-download me-1"></i>
                                        Unduh Surat
                                    </a>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @empty
            <div class="text-center text-muted py-4">
                <i class="cui-inbox fs-3 d-block mb-2"></i>
                Belum ada pengajuan surat
            </div>
        @endforelse

    </div>
</div>


    <div 
    wire:ignore.self
    class="modal fade" 
    id="modalPengajuanSurat" 
    tabindex="-1"
>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Pengajuan Surat OAP
                </h5>
                <button type="button" class="btn-close btn-close-white" data-coreui-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                {{-- Alert Verifikasi --}}
                @if ($pesanVerifikasi)
                    <div class="alert {{ $margaValid ? 'alert-success' : 'alert-warning' }}">
                        {{ $pesanVerifikasi }}
                    </div>
                @endif

                {{-- DATA USER --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input class="form-control" value="{{ $nik }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No KK</label>
                        <input class="form-control" value="{{ $no_kk }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input class="form-control" value="{{ $namaLengkap }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Suku</label>
                        <input class="form-control" value="{{ $suku }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Asal Kabupaten/Kota</label>
                        <input class="form-control" type="text" value="{{ $asalKabupaten }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Ayah</label>
                        <input class="form-control" type="text" value="{{ $namaAyah }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Ibu</label>
                        <input class="form-control" type="text" value="{{ $namaIbu }}" disabled>
                    </div>
                </div>

                <hr>

                {{-- ALASAN --}}
                <div class="mb-3">
                    <label class="form-label">Alasan Pengajuan</label>
                    <select wire:model.live="alasan" class="form-select">
                        <option value="">-- Pilih Alasan --</option>
                        <option value="Pendaftaran CPNS">Pendaftaran CPNS</option>
                        <option value="IPDN">Pendaftaran IPDN</option>
                        <option value="Beasiswa">Beasiswa</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('alasan') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>

                @if ($alasan === 'Lainnya')
                    <div class="mb-3">
                        <input wire:model="alasan_lain" 
                               class="form-control" 
                               placeholder="Masukkan alasan lainnya">
                        @error('alasan_lain') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                @endif

                <hr>

                {{-- UPLOAD DOKUMEN --}}
        <hr>
        <h5 class="mb-3">Unggah Dokumen Pendukung</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle responsive-card">
                <thead class="table-light">
                    <tr>
                        <th width="40">No</th>
                        <th>Dokumen</th>
                        <th>Unggah Berkas</th>
                        <th width="140" class="d-none d-md-table-cell">Status</th>
                        <th width="120" class="d-none d-md-table-cell">Preview</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- FOTO --}}
                    <tr>
                        <td>1</td>
                        <td>Pas Foto 4x6 (Latar Belakang Merah)</td>
                        <td>
                            <input type="file" wire:model.defer="foto" class="form-control w-100" accept="image/jpeg,image/png" capture="environment">
                            <div wire:loading wire:target="foto" class="small text-primary mt-1">
                                Unggah...
                            </div>
                            <small class="text-muted">JPG / PNG • Max 2MB</small>
                            <div class="mt-1 d-md-none">
                                @if($foto)
                                    <span class="badge bg-success">Siap</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </div>
                            {{-- PREVIEW MOBILE --}}
                            <div class="preview-mobile mt-2">
                                @if($foto)
                                    <img src="{{ $foto->temporaryUrl() }}" class="img-thumbnail" style="max-width:120px">
                                @endif
                            </div>
                            @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($foto)
                                <span class="badge bg-success">Siap</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($foto)
                                <img src="{{ $foto->temporaryUrl() }}" class="img-thumbnail mt-2" width="max-width:100px">
                            @endif
                        </td>
                    </tr>

                    {{-- KTP --}}
                    <tr>
                        <td>2</td>
                        <td>KTP</td>
                        <td>
                            <input type="file" wire:model.defer="ktp" class="form-control form-control w-100" accept="image/jpeg,image/png,application/pdf">
                            <div wire:loading wire:target="ktp" class="small text-primary mt-1">
                                Unggah...
                            </div>
                            <small class="text-muted">PDF / JPG • Max 2MB</small>
                            <div class="mt-1 d-md-none">
                                @if($ktp)
                                    <span class="badge bg-success">Siap</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </div>
                            {{-- PREVIEW MOBILE --}}
                            <div class="preview-mobile mt-2">
                                @if($ktp)
                                    @if(str_starts_with($ktp->getMimeType(), 'image'))
                                        <img src="{{ $ktp->temporaryUrl() }}" class="img-thumbnail" style="max-width:120px">
                                    @elseif($ktp->getClientOriginalExtension() === 'pdf')
                                        <span class="badge bg-danger">PDF</span>
                                    @endif
                                @endif
                            </div>
                            @error('ktp') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($ktp)
                                <span class="badge bg-success">Siap</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($ktp)  
                                @if($ktp && str_starts_with($ktp->getMimeType(), 'image'))
                                    <img src="{{ $ktp->temporaryUrl() }}" class="img-thumbnail" width="70">
                                @else
                                    <span class="badge bg-danger">PDF</span>
                                @endif
                            @endif
                        </td>
                    </tr>

                    {{-- KK --}}
                    <tr>
                        <td>3</td>
                        <td>Kartu Keluarga</td>
                        <td>
                            <input type="file" wire:model.defer="kk" class="form-control form-control w-100" accept="image/jpeg,image/png,application/pdf">
                            <div wire:loading wire:target="kk" class="small text-primary mt-1">
                                Unggah...
                            </div>
                            <small class="text-muted">PDF / JPG • Max 2MB</small>
                            <div class="mt-1 d-md-none">
                                @if($kk)
                                    <span class="badge bg-success">Siap</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </div>
                            {{-- PREVIEW MOBILE --}}
                            <div class="preview-mobile mt-2">
                                @if($kk)
                                    @if(str_starts_with($kk->getMimeType(), 'image'))
                                        <img src="{{ $kk->temporaryUrl() }}" class="img-thumbnail" style="max-width:120px">
                                    @elseif($kk->getClientOriginalExtension() === 'pdf')
                                        <span class="badge bg-danger">PDF</span>
                                    @endif
                                @endif
                            </div>
                            @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($kk)
                                <span class="badge bg-success">Siap</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($kk)                   
                                @if($kk && str_starts_with($kk->getMimeType(), 'image'))
                                    <img src="{{ $kk->temporaryUrl() }}" class="img-thumbnail" width="70">
                                @else
                                    <span class="badge bg-danger">PDF</span>
                                @endif
                            @endif
                        </td>
                    </tr>

                    {{-- AKTE --}}
                    <tr>
                        <td>4</td>
                        <td>Akte Kelahiran</td>
                        <td>
                            <input type="file" wire:model.defer="akte" class="form-control form-control w-100" accept="image/jpeg,image/png,application/pdf">
                            <div wire:loading wire:target="akte" class="small text-primary mt-1">
                                Unggah...
                            </div>
                            <small class="text-muted">PDF / JPG • Max 2MB</small>
                            <div class="mt-1 d-md-none">
                                @if($akte)
                                    <span class="badge bg-success">Siap</span>
                                @else
                                    <span class="badge bg-secondary">Belum</span>
                                @endif
                            </div>
                            {{-- PREVIEW MOBILE --}}
                            <div class="preview-mobile mt-2">
                                @if($akte)
                                    @if(str_starts_with($akte->getMimeType(), 'image'))
                                        <img src="{{ $akte->temporaryUrl() }}" class="img-thumbnail" style="max-width:120px">
                                    @elseif($akte->getClientOriginalExtension() === 'pdf')
                                        <span class="badge bg-danger">PDF</span>
                                    @endif
                                @endif
                            </div>
                            @error('akte') <small class="text-danger">{{ $message }}</small> @enderror
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($akte)
                                <span class="badge bg-success">Siap</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell">
                            @if($akte)
                                @if($akte && str_starts_with($akte->getMimeType(), 'image'))
                                    <img src="{{ $akte->temporaryUrl() }}" class="img-thumbnail" width="70">
                                @else
                                    <span class="badge bg-danger">PDF</span>
                                @endif
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">

                <button 
                    class="btn btn-secondary"
                    data-coreui-dismiss="modal">
                    Tutup
                </button>

                @if($margaValid)
                    <button 
                        wire:click="kirim"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="kirim,foto,ktp,kk,akte"
                    >
                        <span wire:loading.remove>Kirim Pengajuan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @else
                    <a href="{{ url('/pengajuan-marga') }}" 
                       class="btn btn-warning">
                        Ajukan Marga
                    </a>
                @endif

            </div>

        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('scrollToTop', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

    });

    window.addEventListener('pengajuanBerhasil', () => {
        const modalEl = document.getElementById('modalPengajuanSurat');
        const modal = coreui.Modal.getInstance(modalEl);

        // 👉 pindahkan fokus ke body / tombol lain
        document.body.focus();

        // 👉 baru tutup modal
        modal.hide();
    });
</script>
