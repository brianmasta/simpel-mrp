<div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4 mx-4 shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="mb-3">Register Akun</h1>
                        <p class="text-body-secondary mb-4">Buat akun Anda untuk mengakses sistem</p>

                        @if (session()->has('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form wire:submit.prevent="register" novalidate>
                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" wire:model.blur="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model.blur="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" wire:model.blur="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" wire:model.blur="password_confirmation" class="form-control" placeholder="Ulangi Password">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Daftar</span>
                                    <span wire:loading>Memproses...</span>
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
