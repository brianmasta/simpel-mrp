<div>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h4>Reset Password</h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="resetPassword">
                <input type="hidden" wire:model="token">

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" wire:model="email" class="form-control" id="email" required>
                    @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" wire:model="password" class="form-control" id="password" required>
                    @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" wire:model="password_confirmation" class="form-control" id="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Reset Password</button>
            </form>
        </div>
    </div>
</div>
</div>
