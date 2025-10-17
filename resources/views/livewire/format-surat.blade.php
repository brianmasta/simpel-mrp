<div>
    
    <div class="container mt-4">

        {{-- Kartu Input Template Surat --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Manajemen Template Surat</h5>
            </div>

            <div class="card-body">
                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Jenis Surat --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Surat</label>
                    <input type="text" class="form-control" wire:model.defer="jenis" placeholder="Contoh: Surat Keterangan OAP">
                    @error('jenis') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Isi Surat HTML --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Isi Surat (HTML)</label>
                    <textarea wire:model.defer="isi_html" class="form-control" rows="12" placeholder="Tulis format HTML surat di sini..."></textarea>
                    <small class="text-muted d-block mt-1">
                        Gunakan tag HTML dan placeholder seperti 
                        <code>[[nama_lengkap]]</code>, <code>[[nik]]</code>, <code>[[kabupaten]]</code>, <code>[[tanggal]]</code>.
                    </small>
                    @error('isi_html') <small class="text-danger d-block">{{ $message }}</small> @enderror
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button class="btn btn-success" wire:click="simpan">Simpan</button>

                    @if($formatId)
                        <button class="btn btn-secondary" wire:click="preview({{ $formatId }})">Preview</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Daftar Template Surat --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">
                Daftar Template Surat
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0 align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>Jenis Surat</th>
                            <th class="text-center" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarFormat as $f)
                            <tr>
                                <td>{{ $f->jenis }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" wire:click="edit({{ $f->id }})">Edit</button>
                                        <button class="btn btn-sm btn-danger" wire:click="hapus({{ $f->id }})">Hapus</button>
                                        <button class="btn btn-sm btn-secondary" wire:click="preview({{ $f->id }})">Preview</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">Belum ada template surat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Preview PDF --}}
        @if ($showPdfModal && $pdfContent)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title">Preview Surat (PDF)</h5>
                            <button type="button" class="btn-close btn-close-white" wire:click="$set('showPdfModal', false)"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe src="data:application/pdf;base64,{{ $pdfContent }}" width="100%" height="650px" style="border:none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Klik area gelap untuk menutup modal --}}
            <div class="modal-backdrop fade show" wire:click="$set('showPdfModal', false)"></div>
        @endif
    </div>
</div>
