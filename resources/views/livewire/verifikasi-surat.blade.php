<div>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            @if ($status === 'valid')
                <div class="card shadow border-success">
                    <div class="card-body text-center">
                        <div class="text-success mb-3">
                            <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="fw-bold text-success">Surat Asli & Terverifikasi ✅</h3>
                        <p class="text-muted">Kode autentikasi ditemukan di sistem <strong>SIMPEL-MRP</strong>.</p>

                        <table class="table table-bordered mt-4 text-start">
                            <tr>
                                <th width="35%">Nomor Surat</th>
                                <td>{{ $surat->nomor_surat }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ mask_nama($surat->profil->nama_lengkap) }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ mask_nik($surat->profil->nik) }}</td>
                            </tr>
                            <tr>
                                <th>Kabupaten</th>
                                <td>{{ $surat->profil->kabupaten->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Terbit</th>
                                <td>{{ $surat->created_at->translatedFormat('d F Y') }}</td>
                            </tr>
                        </table>
@if(auth()->check())
    <a href="{{ route('berkas.akses', [$surat->id, 'surat']) }}"
       class="btn btn-success">
        📄 Lihat Dokumen
    </a>
@else
                        <button wire:click="kirimOtp({{ $surat->id }})"
                                wire:loading.attr="disabled"
                                class="btn btn-primary">

                            <span wire:loading.remove wire:target="kirimOtp">
                                🔐 Lihat Dokumen
                            </span>

                            <span wire:loading wire:target="kirimOtp">
                                ⏳ Mengirim OTP...
                            </span>

                        </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow border-danger">
                    <div class="card-body text-center">
                        <div class="text-danger mb-3">
                            <i class="bi bi-x-circle-fill" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="fw-bold text-danger">Surat Tidak Ditemukan ❌</h3>
                        <p class="text-muted mb-3">
                            Kode autentikasi <strong>{{ $kode }}</strong> tidak terdaftar dalam sistem.
                        </p>
                        <div class="alert alert-warning">
                            Pastikan surat ini diterbitkan resmi oleh <br>
                            <strong>Majelis Rakyat Papua Provinsi Papua Tengah</strong>.
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

@if($showPasswordModal)
<div class="modal fade show d-block" style="background: rgba(0,0,0,0.5)" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(3px);">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Masukkan OTP</h5>
            </div>

            <div class="modal-body">
                <input type="text"
                    wire:model="inputOtp"
                    wire:keydown.enter="verifikasiOtp"
                    class="form-control text-center fw-bold"
                    style="letter-spacing: 5px; font-size: 1.5rem"
                    placeholder="••••••"
                    maxlength="6"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    autofocus>
                <small class="text-muted">OTP dikirim ke Email & WhatsApp</small>
                <small class="text-muted d-block mt-2">
                    🔒 Kode OTP bersifat rahasia dan berlaku selama 5 menit.
                </small>
            </div>

            <div class="modal-footer">
                <button wire:click="kirimOtp({{ $surat->id }})"
                        class="btn btn-link text-decoration-none">
                    Kirim ulang OTP
                </button>
                <button wire:click="$set('showPasswordModal', false)"
                        class="btn btn-secondary">
                    Batal
                </button>

                <button wire:click="verifikasiOtp"
                        class="btn btn-success">
                    Verifikasi
                </button>
            </div>

        </div>
    </div>
</div>
@endif

</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('download-file', (data) => {
            console.log('DOWNLOAD:', data.url);
            window.location.href = data.url;
        });
    });
</script>
</div>
