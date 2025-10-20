<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Verifikasi Email</h5>
                </div>
                <div class="card-body">

                    <p class="text-muted">
                        Terima kasih sudah mendaftar! <br>
                        Sebelum melanjutkan, silakan verifikasi alamat email Anda dengan mengeklik link yang telah kami kirim ke email Anda.
                    </p>

                    @if ($message)
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <button
                        wire:click="resend"
                        wire:loading.attr="disabled"
                        class="btn btn-primary w-100 mb-2"
                    >
                        <span wire:loading.remove wire:target="resend">
                            Kirim Ulang Email Verifikasi
                        </span>
                        <span wire:loading wire:target="resend">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Mengirim...
                        </span>
                    </button>

                    <button
                        wire:click="logout"
                        wire:loading.attr="disabled"
                        class="btn btn-outline-danger w-100"
                    >
                        <span wire:loading.remove wire:target="logout">
                            Logout
                        </span>
                        <span wire:loading wire:target="logout">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Logout...
                        </span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
