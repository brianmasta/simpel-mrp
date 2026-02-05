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
                                {{ $user->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="text-center">
                                <button wire:click="view({{ $user->id }})"
                                    class="btn btn-sm btn-info"
                                    title="Lihat Profil">
                                    <i class="cil-user"></i>
                                </button>

                                <button wire:click="edit({{ $user->id }})"
                                    class="btn btn-sm btn-primary"
                                    title="Edit">
                                    <i class="cil-pencil"></i>
                                </button>

                                <button wire:click="resetPassword({{ $user->id }})"
                                    class="btn btn-sm btn-secondary"
                                    title="Reset Password">
                                    <i class="cil-reload"></i>
                                </button>

                                <button wire:click="delete({{ $user->id }})"
                                    onclick="confirm('Yakin hapus akun ini?') || event.stopImmediatePropagation()"
                                    class="btn btn-sm btn-danger"
                                    title="Hapus">
                                    <i class="cil-trash"></i>
                                </button>
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
    @if($showViewModal)
<div class="modal fade show d-block" style="background:rgba(0,0,0,.5)">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="cil-user me-2"></i> Detail Akun Pengguna
                </h5>
                <button wire:click="$set('showViewModal', false)" class="btn-close"></button>
            </div>

            <div class="modal-body">
                <h6 class="text-primary">Data Akun</h6>
                <table class="table table-sm">
                    <tr><th width="30%">Nama</th><td>{{ $viewUser->name ?? '-' }}</td></tr>
                    <tr><th>Email</th><td>{{ $viewUser->email ?? '-' }}</td></tr>
                    <tr><th>Role</th><td>{{ ucfirst($viewUser->role ?? '-') }}</td></tr>
                </table>

                <hr>

                <h6 class="text-primary">Data Profil</h6>
                <table class="table table-sm">
                    <tr><th>NIK</th><td>{{ $viewUser->profil->nik ?? '-' }}</td></tr>
                    <tr><th>Alamat</th><td>{{ $viewUser->profil->alamat ?? '-' }}</td></tr>
                    <tr><th>No HP</th><td>{{ $viewUser->profil->no_hp ?? '-' }}</td></tr>
                    <tr><th>Kabupaten</th><td>{{ $viewUser->profil->kabupaten->nama ?? '-' }}</td></tr>
                </table>
            </div>

            <div class="modal-footer">
                <button wire:click="$set('showViewModal', false)" class="btn btn-secondary">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endif
</div>
