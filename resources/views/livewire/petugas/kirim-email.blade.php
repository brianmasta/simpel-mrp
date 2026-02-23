<div>
<div class="card">
    <div class="card-header fw-bold">Kirim Email ke Pengguna</div>

    <div class="card-body">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <label>Tujuan</label>
            <select class="form-control" wire:model="user_id">
                <option value="">-- Pilih Pengguna --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Judul Email</label>
            <input type="text" class="form-control" wire:model="judul">
        </div>

        <div class="mb-3">
            <label>Isi Pesan</label>
            <textarea class="form-control" rows="5" wire:model="pesan"></textarea>
        </div>

        <button wire:click="kirim" class="btn btn-primary">
            Kirim Email
        </button>
    </div>
</div>
</div>
