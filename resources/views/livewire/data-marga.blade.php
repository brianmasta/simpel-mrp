<div>
    <div class="card mb-4">
    <div class="card-header">
        <strong>
            {{ $selectedId ? 'Edit Marga OAP' : 'Tambah Marga OAP' }}
        </strong>
    </div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @error('marga')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label" for="">Wilayah Adat</label>
                <input class="form-control" type="text" wire:model="wilayah_adat">
                @error('wilayah_adat') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="">Suku</label>
                <input class="form-control" type="text" wire:model="suku">
                @error('suku') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="">Marga</label>
                <input class="form-control" type="text" wire:model="marga">
                @error('suku') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label for="formFile" class="form-label">Berkas Pendukung</label>
                <input class="form-control" type="file" id="formFile" wire:model="berkas">
                {{-- <div id="" class="form-text">Sesuai KTP.</div> --}}
                @error('berkas') <span class="text-danger">{{ $message }}</span> @enderror

                @if($existingBerkas)
                    <div class="mt-2">
                        <small>Berkas saat ini: 
                            <a href="{{ asset('storage/' . $existingBerkas) }}" target="_blank">
                                {{ basename($existingBerkas) }}
                            </a>
                        </small>
                    </div>
                @endif

            </div>
            <hr>
            <div class="d-grid gap-2">
                    <button class="btn btn-primary">
                        {{ $selectedId ? 'Update' : 'Simpan' }}
                    </button>
                    @if($selectedId)
                        <button type="button" wire:click="resetForm" class="btn btn-warning">Batal</button>
                    @endif
            </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
    <div class="card-header"><strong>Data Marga OAP</strong></div>
        <div class="card-body">
            <div class="mb-3">
            <input type="text" class="form-control mb-3" placeholder="Cari marga, suku, atau wilayah adat..." wire:model.live="search">
            </div>

            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Marga</th>
                    <th scope="col">Suku</th>
                    <th scope="col">Wilayah Adat</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">User</th>
                    <th scope="col">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($margas as $index => $item)
                    <tr>
                    <th scope="row">{{ $index + 1 }}</th>
                    <td>{{ $item->marga }}</td>
                    <td>{{ $item->suku }}</td>
                    <td>{{ $item->wilayah_adat }}</td>
                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>
                        <button wire:click="edit({{ $item->id }})" class="btn btn-primary btn-sm">Edit</button>
                        <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                        {{ $margas->links() }}


    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data marga ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>
</div>

<!-- Script untuk trigger modal -->
<script>
    window.addEventListener('show-delete-modal', event => {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });

    window.addEventListener('hide-delete-modal', event => {
        const modalEl = document.getElementById('deleteModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    });
</script>



