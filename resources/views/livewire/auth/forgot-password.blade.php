<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white">
                <h4 class="mb-0">Lupa Password</h4>
            </div>
            <div class="card-body p-4">

                {{-- Notifikasi sukses --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                @endif

                <form wire:submit.prevent="sendResetLink">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" wire:model="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
                        @error('email') 
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Kirim Link Reset
                    </button>
                </form>

            </div>
            <div class="card-footer text-center small text-muted">
                Kembali ke <a href="{{ route('login') }}">halaman login</a>
            </div>
        </div>
    </div>
</div>
