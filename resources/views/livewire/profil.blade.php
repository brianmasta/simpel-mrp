<div>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white"><strong>Data Profil</strong></div>
        <div class="card-body">
                
            {{-- NIK --}}
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input class="form-control @error('nik') is-invalid @enderror" type="number" wire:model.live="nik" placeholder="NIK Sesuai KTP" inputmode="numeric" pattern="[0-9]*" maxlength="16" required>
                <div class="form-text">NIK sesuai KTP *</div>
                @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label class="form-label">No KK</label>
                <input class="form-control @error('no_kk') is-invalid @enderror" type="number" wire:model.live="no_kk" placeholder="Nomor Kartu Keluarga" inputmode="numeric" pattern="[0-9]*" maxlength="16" required>
                <div class="form-text">Nomor Kartu Keluarga *</div>
                @error('no_kk')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input class="form-control @error('nama_lengkap') is-invalid @enderror" type="text" wire:model.live="nama_lengkap" placeholder="Nama lengkap sesuai KTP" required>
                <div class="form-text">Nama lengkap sesuai KTP *</div>
                @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                    {{-- Hasil deteksi marga --}}
                @if($margaDitemukan === true)
                    <div class="alert alert-success mt-2">
                        ✅ Marga <strong>{{ $marga }}</strong> terdaftar di database MRP.
                        <br>
                        <small class="text-muted">Diverifikasi otomatis {{ $nama_lengkap ? 'dari Nama Lengkap' : 'dari Nama Ibu' }}</small>
                    </div>
                @elseif($margaDitemukan === false)
                    <div class="alert alert-warning mt-2">
                        ⚠️ Marga <strong>{{ $marga }}</strong> tidak ditemukan di database MRP.
                        <br>
                        <small class="text-muted">Diverifikasi otomatis {{ $nama_lengkap ? 'dari Nama Lengkap' : 'dari Nama Ibu' }}</small>
                        <br>
                        Silakan <a href="{{ url('/pengajuan-marga') }}" class="alert-link">ajukan penambahan marga</a> ke MRP.
                    </div>
                @endif
            </div>
            
            <hr>

            {{-- Nama Ayah --}}
            <div class="mb-3">
                <label class="form-label">Nama Ayah</label>
                <input class="form-control @error('nama_ayah') is-invalid @enderror" type="text" wire:model.live="nama_ayah" placeholder="Nama ayah kandung" required>
                <div class="form-text">Nama ayah kandung *</div>
                @error('nama_ayah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nama Ibu --}}
            <div class="mb-3">
                <label class="form-label">Nama Ibu</label>
                <input class="form-control @error('nama_ibu') is-invalid @enderror" type="text" wire:model.live="nama_ibu" placeholder="Nama ibu kandung" required>
                <div class="form-text">Nama ibu kandung *</div>
                @error('nama_ibu')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catatan Marga --}}
            @if($margaDitemukan === false)
                <div class="mb-3">
                    <div class="alert alert-danger">
                        <strong>Catatan:</strong> Jika marga Anda belum terdaftar, silakan 
                        <a href="/pengajuan-marga" class="alert-link">ajukan penambahan marga</a> 
                        ke Majelis Rakyat Papua.
                    </div>
                </div>
            @endif

            {{-- Tempat Lahir --}}
            <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input class="form-control @error('tempat_lahir') is-invalid @enderror" type="text" wire:model.live="tempat_lahir">
                <div class="form-text">Sesuai KTP *</div>
                @error('tempat_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input class="form-control @error('tanggal_lahir') is-invalid @enderror" type="date" wire:model.live="tanggal_lahir" placeholder="Tanggal Lahir" required>
                <div class="form-text">Sesuai KTP *</div>
                @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" wire:model.live="jenis_kelamin" required>
                    <option value="">--Pilih--</option>
                    <option value="laki-laki">Laki-Laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
                <div class="form-text">Pilih sesuai KTP *</div>
                @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



    {{-- Provinsi --}}
    <div class="mb-3 position-relative" wire:click.outside="$set('provinsis', [])">
        <label>Provinsi</label>
        <input type="text" class="form-control @error('provinsi_id') is-invalid @enderror" placeholder="Ketik provinsi..."  wire:model.live="searchProvinsi" required>
        <div class="form-text">Pilih provinsi sesuai alamat KK/KTP *</div>
        @error('provinsi_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        @if(!empty($provinsis))
            <ul class="list-group position-absolute w-100" style="z-index: 10;">
                @foreach($provinsis as $prov)
                    <li class="list-group-item list-group-item-action" wire:click="selectProvinsi({{ $prov->id }})" style="cursor: pointer;">
                        {{ $prov->nama }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Kabupaten --}}
    <div class="mb-3 position-relative" wire:click.outside="$set('kabupatens', [])">
        <label>Kabupaten</label>
        <input type="text" class="form-control @error('kabupaten_id') is-invalid @enderror" placeholder="Ketik kabupaten..." wire:model.live="searchKabupaten" required @disabled(!$provinsi_id)>
        <div class="form-text">Pilih kabupaten sesuai alamat KK/KTP *</div>
        @error('kabupaten_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
        @if(!empty($kabupatens))
            <ul class="list-group position-absolute w-100" style="z-index: 10;">
                @foreach($kabupatens as $kab)
                    <li class="list-group-item list-group-item-action" wire:click="selectKabupaten({{ $kab->id }})" style="cursor: pointer;">
                        {{ $kab->nama }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Kecamatan --}}
    <div class="mb-3 position-relative" wire:click.outside="$set('kecamatans', [])">
        <label>Kecamatan</label>
        <input type="text" class="form-control @error('kecamatan_id') is-invalid @enderror" placeholder="Ketik kecamatan..." wire:model.live="searchKecamatan" required @disabled(!$kabupaten_id)>
        <div class="form-text">Pilih kecamatan sesuai alamat KK/KTP *</div>
        @error('kecamatan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(!empty($kecamatans))
            <ul class="list-group position-absolute w-100" style="z-index: 10;">
                @foreach($kecamatans as $kec)
                    <li class="list-group-item list-group-item-action" wire:click="selectKecamatan({{ $kec->id }})" style="cursor: pointer;">
                        {{ $kec->nama }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Kelurahan --}}
    <div class="mb-3 position-relative" wire:click.outside="$set('kelurahans', [])">
        <label>Kelurahan</label>
        <input type="text" class="form-control @error('kelurahan_id') is-invalid @enderror" placeholder="Ketik kelurahan..." wire:model.live="searchKelurahan" required @disabled(!$kecamatan_id)>
        <div class="form-text">Pilih kelurahan sesuai alamat KK/KTP *</div>
        @error('kelurahan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(!empty($kelurahans))
            <ul class="list-group position-absolute w-100" style="z-index: 10;">
                @foreach($kelurahans as $kel)
                    <li class="list-group-item list-group-item-action" wire:click="selectKelurahan({{ $kel->id }})" style="cursor: pointer;">
                        {{ $kel->nama }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

            {{-- Alamat --}}
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Contoh: Jl. Yos Sudarso No.12 RT 02 RW 01" wire:model.live="alamat" required></textarea>
                <div class="form-text">Alamat lengkap sesuai KTP *</div>
                @error('alamat')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <hr>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" wire:model.live="email" disabled>
                <div class="form-text">Email terdaftar (tidak dapat diubah)</div>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input class="form-control @error('no_hp') is-invalid @enderror" type="number" wire:model.live="no_hp" placeholder="Contoh: 081234567890" required inputmode="numeric" pattern="[0-9]*">
                <div class="form-text">Nomor HP aktif (untuk verifikasi & notifikasi) *</div>
                @error('no_hp') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <hr>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <div class="d-grid gap-2">
                <button 
                    class="btn btn-primary" 
                    wire:click="save"
                    wire:loading.attr="disabled" 
                    wire:target="save"
                >
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('ocr-success', event => {
    alert(event.detail.message);
});

</script>
