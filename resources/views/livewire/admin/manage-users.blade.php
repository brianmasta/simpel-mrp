<div>
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h5 class="mb-0">Manajemen Akun Pengguna</h5>
            <input wire:model.live="search" type="text" class="form-control w-25" placeholder="Cari nama/email...">
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input wire:model="name" type="text" class="form-control" placeholder="Nama Lengkap">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3">
                        <input wire:model="email" type="email" class="form-control" placeholder="Email">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-2">
                        <select wire:model="role" class="form-select">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="pengguna">Pengguna</option>
                        </select>
                        @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    @if (!$isEditing)
                        <div class="col-md-2">
                            <input wire:model="password" type="password" class="form-control" placeholder="Password">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    @endif

                    <div class="col-md-2">
                        <button class="btn btn-{{ $isEditing ? 'warning' : 'success' }} w-100">
                            {{ $isEditing ? 'Update' : 'Tambah' }}
                        </button>
                    </div>
                </div>
            </form>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'petugas' ? 'warning' : 'info') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-primary">Edit</button>
                                <button wire:click="resetPassword({{ $user->id }})" class="btn btn-sm btn-secondary">Reset</button>
                                <button wire:click="delete({{ $user->id }})" onclick="confirm('Yakin hapus akun ini?') || event.stopImmediatePropagation()" class="btn btn-sm btn-danger">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $users->links() }}
        </div>
    </div>
</div>
