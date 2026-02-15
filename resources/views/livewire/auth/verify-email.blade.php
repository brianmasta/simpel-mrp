<div>
<div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card p-4 shadow-sm">
                    <div class="card-body text-center">

                        {{-- ICON --}}
<div class="mb-3 d-flex justify-content-center">
    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
        <svg
            width="56"
            height="56"
            viewBox="0 0 24 24"
            fill="none"
            stroke="var(--cui-primary)"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
            <polyline points="3 7 12 13 21 7"></polyline>
        </svg>
    </div>
</div>

                        {{-- TITLE --}}
                        <h4 class="fw-bold mb-2">Verifikasi Email</h4>

                        {{-- DESC --}}
                        <p class="text-body-secondary mb-4">
                            Terima kasih telah mendaftar di <strong>SIMPEL-MRP</strong>.<br>
                            Silakan verifikasi alamat email Anda dengan mengeklik tautan
                            yang telah kami kirim ke email Anda.
                        </p>

                        {{-- SUCCESS MESSAGE --}}
                        @if ($message)
                            <div class="alert alert-success text-start">
                                {{ $message }}
                            </div>
                        @endif

                        {{-- RESEND --}}
                        <button
                            wire:click="resend"
                            wire:loading.attr="disabled"
                            @disabled($cooldown > 0)
                            class="btn btn-primary w-100 mb-2"
                        >
                            <span wire:loading.remove wire:target="resend">
                                @if ($cooldown > 0)
                                    Kirim Ulang ({{ $cooldown }} dtk)
                                @else
                                    Kirim Ulang Email Verifikasi
                                @endif
                            </span>

                            <span wire:loading wire:target="resend">
                                <span class="spinner-border spinner-border-sm me-1"
                                    role="status" aria-hidden="true"></span>
                                Mengirim...
                            </span>
                        </button>
                        @if ($cooldown > 0)
                            <div wire:poll.1s="tick"></div>
                            <small class="text-body-secondary d-block text-center">
                                Anda dapat mengirim ulang email setelah {{ $cooldown }} detik
                            </small>
                        @endif

                        {{-- LOGOUT --}}
                        <button
                            wire:click="logout"
                            wire:loading.attr="disabled"
                            class="btn btn-outline-danger w-100"
                        >
                            <span wire:loading.remove wire:target="logout">
                                Logout
                            </span>
                            <span wire:loading wire:target="logout">
                                <span class="spinner-border spinner-border-sm me-1"
                                    role="status" aria-hidden="true"></span>
                                Logout...
                            </span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>