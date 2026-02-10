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
</style>

<div class="card mb-4">
    <div class="card-header bg-primary text-white"><strong>Riwayat Pengajuan Surat OAP</strong></div>
    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nomor Surat</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nomor_surat ?? '-' }}</td>
                            <td>{{ $item->alasan ?? '-' }}</td>
                            <td>
    <span class="badge 
        bg-{{ 
            $item->status === 'terbit' || $item->status === 'valid' ? 'success' :
            ($item->status === 'perlu_perbaikan' ? 'danger' :
            ($item->status === 'verifikasi' ? 'warning' : 'secondary'))
        }}">
        {{ strtoupper($item->status) }}
    </span>
                            </td>
                            <td>{{ $item->created_at?->format('d-m-Y') ?? '-' }}</td>
                            <td>

    @if($item->status === 'perlu_perbaikan')
        <a href="{{ route('perbaikan-berkas', $item->id) }}"
           class="btn btn-sm btn-danger">
            <i class="bi bi-tools me-1"></i> Perbaiki Berkas
        </a>

    @elseif($item->file_surat && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->file_surat))
        <a href="{{ \Illuminate\Support\Facades\Storage::url($item->file_surat) }}?v={{ time() }}"
           target="_blank"
           class="btn btn-sm btn-warning">
            <i class="bi bi-download me-1"></i> Unduh
        </a>

    @else
        <span class="text-muted">Belum tersedia</span>
    @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pengajuan surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>


    {{-- FORM PENGAJUAN SURAT --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Pengajuan Surat OAP</strong></div>
        <div class="card-body">
            @if ($pesanVerifikasi)
                <div class="alert {{ $margaValid ? 'alert-success' : 'alert-warning' }}">
                    {{ $pesanVerifikasi }}
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input class="form-control" type="text" value="{{ $nik }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">No KK</label>
                <input class="form-control" type="text" value="{{ $no_kk }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input class="form-control" type="text" value="{{ $namaLengkap }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Suku</label>
                <input class="form-control" type="text" value="{{ $suku }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Asal Kabupaten/Kota</label>
                <input class="form-control" type="text" value="{{ $asalKabupaten }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Ayah</label>
                <input class="form-control" type="text" value="{{ $namaAyah }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Ibu</label>
                <input class="form-control" type="text" value="{{ $namaIbu }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Alasan Pengajuan</label>
                <select wire:model.live="alasan" class="form-select">
                    <option value="">-- Pilih Alasan --</option>
                    <option value="Pendaftaran CPNS">Pendaftaran CPNS</option>
                    <option value="Pendaftaran IPDN">Pendaftaran IPDN</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                @error('alasan') 
                    <small class="text-danger">{{ $message }}</small> 
                @enderror
            </div>

            {{-- Input tambahan jika alasan = Lainnya --}}
            @if ($alasan === 'Lainnya')
                <div class="mb-3">
                    <label class="form-label">Tuliskan Alasan Lainnya</label>
                    <input type="text" wire:model="alasan_lain" class="form-control" placeholder="Masukkan alasan lain...">
                    @error('alasan_lain') 
                        <small class="text-danger">{{ $message }}</small> 
                    @enderror
                </div>
            @endif

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

<div wire:loading wire:target="foto,ktp,kk,akte" class="text-primary small">
    Uploading...
</div>


            <hr>

            <div class="d-grid gap-2">
                @if($margaValid)
                    {{-- Tombol Kirim Surat --}}
                    <button 
                        wire:click="kirim" 
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Kirim & Terbitkan Surat</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @else
                    {{-- Tombol Ajukan Marga --}}
                    <a 
                        href="{{ url('/pengajuan-marga') }}" 
                        class="btn btn-warning"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Ajukan Marga
                    </a>
                @endif
            </div>

            @if(!$margaValid)
                <div class="alert alert-warning mt-3">
                    ⚠️ Marga Anda belum terdaftar di database MRP.  
                    Silakan ajukan penambahan marga terlebih dahulu agar dapat mengajukan Surat OAP.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('scrollToTop', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
