<div>
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

                    <button wire:click="resend" class="btn btn-primary w-100 mb-2">
                        Kirim Ulang Email Verifikasi
                    </button>

                    <button wire:click="logout" class="btn btn-outline-danger w-100">
                        Logout
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
</div>
