<div>
<div class="bg-body-tertiary min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-group d-block d-md-flex row shadow-sm">

                    {{-- FORM REGISTER --}}
                    <div class="card col-md-7 p-4 mb-0">
                        <div class="card-body">
                            <h1 class="fw-bold mb-2">Register</h1>
                            <p class="text-body-secondary mb-4">
                                Buat akun untuk mengakses <strong>SIMPEL-MRP</strong>
                            </p>

                            <form wire:submit.prevent="register">

                                {{-- NAMA --}}
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                        </svg>
                                    </span>
                                    <input type="text"
                                        wire:model.blur="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Nama Lengkap">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- EMAIL --}}
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                         <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217l-8 4.8-8-4.8z"/>
                                        <path d="M0 6.383l5.803 3.482L0 13.617zM6.761 10.083L16 6.383v7.234z"/>
                                        </svg>
                                    </span>
                                    <input type="email"
                                        wire:model.blur="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PASSWORD --}}
                                <div class="input-group mb-3">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2"/>
                                        </svg>
                                    </span>
                                    <input type="password"
                                        id="password"
                                        wire:model.blur="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password', this)">
                                        <svg class="icon">
                                            <!-- eye -->
                                            <path d="M16 8s-3-5-8-5-8 5-8 5 3 5 8 5 8-5 8-5z"/>
                                            <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                @enderror

                                {{-- KONFIRMASI PASSWORD --}}
                                <div class="input-group mb-4">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2"/>
                                        </svg>
                                    </span>
                                    <input type="password"
                                        id="password_confirmation"
                                        wire:model.blur="password_confirmation"
                                        class="form-control"
                                        placeholder="Konfirmasi Password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation', this)">
                                        <svg class="icon">
                                            <!-- eye -->
                                            <path d="M16 8s-3-5-8-5-8 5-8 5 3 5 8 5 8-5 8-5z"/>
                                            <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                    </button>
                                </div>
                                {{-- PERSETUJUAN --}}
                                <div class="form-check mb-3">
                                    <input class="form-check-input @error('agree') is-invalid @enderror"
                                        type="checkbox"
                                        id="agree"
                                        wire:model="agree">

                                    <label class="form-check-label small" for="agree">
                                        Saya menyatakan telah membaca dan menyetujui
                                        <a href="#"
                                            wire:click.prevent="$set('showSyarat', true)">
                                            Syarat dan Ketentuan
                                        </a>
                                        serta
                                        <a href="#"
                                            wire:click.prevent="$set('showPrivasi', true)">
                                            Kebijakan Privasi
                                        </a>
                                        SIMPEL-MRP.
                                    </label>

                                    @error('agree')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- CAPTCHA --}}
                                <div class="mb-3" wire:ignore>
                                    <div class="g-recaptcha"
                                        data-sitekey="{{ config('services.recaptcha.site_key') }}"
                                        data-callback="onRegisterRecaptchaSuccess">
                                    </div>
                                    @error('recaptcha')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- BUTTON --}}
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="submit"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove>Daftar</span>
                                        <span wire:loading>Memproses...</span>
                                    </button>

                                    <a href="{{ route('login') }}"
                                        class="btn btn-outline-secondary">
                                        Sudah punya akun? Login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- PANEL INFO --}}
                    <div class="card col-md-5 text-white bg-primary py-5">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <h2 class="fw-bold mb-3">SIMPEL-MRP</h2>
                            <p>
                                Sistem Informasi Pelayanan Majelis Rakyat Papua
                                untuk pelayanan publik yang cepat, transparan,
                                dan akuntabel.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@if($showSyarat)
<div class="modal fade show d-block"
     tabindex="-1"
     style="background: rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Syarat dan Ketentuan</h5>
                <button type="button"
                        class="btn-close"
                        wire:click="$set('showSyarat', false)"></button>
            </div>

            <div class="modal-body small">
                <p>
                    SIMPEL-MRP merupakan Sistem Informasi Pelayanan Majelis Rakyat Papua
                    yang diselenggarakan dalam rangka penerapan Sistem Pemerintahan Berbasis
                    Elektronik (SPBE) sesuai dengan ketentuan peraturan perundang-undangan.
                </p>
                <p>
                    Pengguna wajib memberikan data dan informasi yang benar, lengkap,
                    dan dapat dipertanggungjawabkan serta menggunakan sistem ini
                    sesuai dengan tujuan dan fungsinya.
                </p>
                <p>
                    Setiap pelanggaran terhadap ketentuan penggunaan SIMPEL-MRP
                    dapat dikenakan sanksi administratif dan/atau tindakan hukum
                    sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.
                </p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        wire:click="$set('showSyarat', false)">
                    Tutup
                </button>

                <button class="btn btn-primary"
                        wire:click="$set('agree', true); $set('showSyarat', false)">
                    Saya Setuju
                </button>
            </div>

        </div>
    </div>
</div>
@endif

@if($showPrivasi)
<div class="modal fade show d-block"
     tabindex="-1"
     style="background: rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Kebijakan Privasi</h5>
                <button type="button"
                        class="btn-close"
                        wire:click="$set('showPrivasi', false)"></button>
            </div>

            <div class="modal-body small">
                <p>
                    Data pribadi pengguna diproses oleh SIMPEL-MRP sesuai dengan ketentuan
                    peraturan perundang-undangan yang berlaku mengenai perlindungan data pribadi.
                </p>
                <p>
                    Jenis data pribadi yang dapat dikumpulkan dan diproses meliputi:
                </p>
                <ul>
                    <li>Nama lengkap;</li>
                    <li>Alamat surat elektronik (email);</li>
                    <li>Dokumen administrasi pendukung;</li>
                    <li>Informasi teknis berupa alamat IP untuk kepentingan keamanan sistem dan audit.</li>
                </ul>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        wire:click="$set('showPrivasi', false)">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endif
</div>

{{-- SCRIPT --}}
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const svg = btn.querySelector('svg');

    if (!input || !svg) return;

    if (input.type === 'password') {
        input.type = 'text';
        svg.innerHTML = `
            <path d="M13.359 11.238l2.122 2.122-1.414 1.414-14-14L1.48.354l2.47 2.47C5.036 2.38 6.47 2 8 2c5 0 8 6 8 6a13.1 13.1 0 0 1-2.64 3.238z"/>
            <path d="M11.297 9.176A3 3 0 0 0 6.824 4.703l1.414 1.414A1 1 0 0 1 9.883 7.76z"/>
        `;
    } else {
        input.type = 'password';
        svg.innerHTML = `
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
            <path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
        `;
    }
}

    function onRegisterRecaptchaSuccess(token) {
        Livewire.find(@this.__instance.id)
            .set('recaptchaToken', token);
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('reset-recaptcha', () => {
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
            }
        });
    });
</script>
</div>