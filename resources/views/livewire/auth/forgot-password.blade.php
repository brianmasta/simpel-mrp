<div>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h4>Lupa Password</h4>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form wire:submit.prevent="sendResetLink">
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" wire:model="email" class="form-control" id="email" required>
                    @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
            </form>
        </div>
    </div>
</div>
</div>
