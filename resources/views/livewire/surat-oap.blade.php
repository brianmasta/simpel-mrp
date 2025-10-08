<div>
    {{-- RIWAYAT PENGAJUAN SURAT --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Riwayat Pengajuan Surat OAP</strong></div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

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
                                @if ($item->status === 'terbit')
                                    <span class="badge bg-success">Terbit</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
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
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pengajuan surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- FORM PENGAJUAN SURAT --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Pengajuan Surat OAP</strong></div>
        <div class="card-body">
            @if ($pesanVerifikasi)
                <div class="alert {{ $margaValid ? 'alert-success' : 'alert-warning' }}">
                    {!! $pesanVerifikasi !!}
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input class="form-control" type="text" value="{{ $nik }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input class="form-control" type="text" value="{{ $namaLengkap }}" disabled>
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
                <select wire:model="alasan" class="form-select">
                    <option value="">-- Pilih Alasan --</option>
                    <option value="Pendaftaran CPNS">Pendaftaran CPNS</option>
                    <option value="Pendaftaran IPDN">Pendaftaran IPDN</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                @error('alasan') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <hr>
            <h5 class="mb-3">Unggah Dokumen Pendukung</h5>

            <div class="mb-3">
                <label class="form-label">Foto Diri</label>
                <input type="file" wire:model="foto" class="form-control">
                @if ($foto)
                    <img src="{{ $foto->temporaryUrl() }}" alt="Foto Preview" class="img-thumbnail mt-2" width="120">
                @endif
                @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">KTP</label>
                <input type="file" wire:model="ktp" class="form-control">
                @error('ktp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Kartu Keluarga (KK)</label>
                <input type="file" wire:model="kk" class="form-control">
                @error('kk') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Akte Kelahiran</label>
                <input type="file" wire:model="akte" class="form-control">
                @error('akte') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <hr>

            <div class="d-grid gap-2">
                <button 
                    wire:click="kirim" 
                    class="btn btn-primary"
                    wire:loading.attr="disabled"
                    @disabled(!$margaValid)
                >
                    <span wire:loading.remove>Kirim & Terbitkan Surat</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </div>

            @if(!$margaValid)
                <div class="text-danger mt-3">
                    ⚠️ Tombol kirim dinonaktifkan karena marga tidak terdaftar di database MRP.
                </div>
            @endif
        </div>
    </div>
</div>
