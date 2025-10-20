<div>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Reset Password</h4>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="resetPassword">
                        <input type="hidden" wire:model="token">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <input type="email" wire:model="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
                            @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <input type="password" wire:model="password" class="form-control" id="password" placeholder="Masukkan password baru" required>
                            @error('password') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" wire:model="password_confirmation" class="form-control" id="password_confirmation" placeholder="Konfirmasi password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading.remove>Reset Password</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center small text-muted">
                    <a href="{{ route('login') }}" class="text-decoration-none">Kembali ke halaman login</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
