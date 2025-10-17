<div>
    <div class="card mb-4">
        <div class="card-header bg-dark text-white"><strong>Profil</strong></div>
        <div class="card-body">

            {{-- <div class="mb-3">
                <label>KTP (Scan / Foto)</label>
                <input type="file" wire:model="foto_ktp" accept="image/*" class="form-control">
                <div wire:loading wire:target="foto_ktp">Membaca data dari KTP...</div>
            </div>

            <div class="mb-3">
                <label>Kartu Keluarga (Scan / Foto)</label>
                <input type="file" wire:model="foto_kk" accept="image/*" class="form-control">
                <div wire:loading wire:target="foto_kk">Membaca data dari KK...</div>
            </div> --}}
                
            {{-- NIK --}}
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input class="form-control" type="number" wire:model.live="nik">
                <div class="form-text">NIK sesuai KTP.</div>
                @error('nik') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- NIK --}}
            <div class="mb-3">
                <label class="form-label">No KK</label>
                <input class="form-control" type="number" wire:model.live="no_kk">
                <div class="form-text">No KK yang sesuai </div>
                @error('no_kk') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input class="form-control" type="text" wire:model.live="nama_lengkap">
                <div class="form-text">Nama lengkap sesuai KTP.</div>
                @error('nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror

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
                <input class="form-control" type="text" wire:model.live="nama_ayah">
                @error('nama_ayah') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- Nama Ibu --}}
            <div class="mb-3">
                <label class="form-label">Nama Ibu</label>
                <input class="form-control" type="text" wire:model.live="nama_ibu">
                @error('nama_ibu') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- Catatan Marga --}}
            <div class="mb-3">
                <div class="alert alert-danger">
                    <strong>Catatan:</strong> Jika marga Anda belum terdaftar, silakan 
                    <a href="/pengajuan-marga" class="alert-link">ajukan penambahan marga</a> 
                    ke Majelis Rakyat Papua.
                </div>
            </div>

            {{-- Tempat Lahir --}}
            <div class="mb-3">
                <label class="form-label">Tempat Lahir</label>
                <input class="form-control" type="text" wire:model.live="tempat_lahir">
                <div class="form-text">Sesuai KTP.</div>
                @error('tempat_lahir') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input class="form-control" type="date" wire:model.live="tanggal_lahir">
                <div class="form-text">Sesuai KTP.</div>
                @error('tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select class="form-control" wire:model.live="jenis_kelamin">
                    <option value="">--Pilih--</option>
                    <option value="laki-laki">Laki-Laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
                <div class="form-text">Sesuai KTP.</div>
                @error('jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



    {{-- Provinsi --}}
    <div class="mb-3 position-relative">
        <label>Provinsi</label>
        <input type="text" class="form-control" placeholder="Ketik provinsi..." wire:model.live="searchProvinsi">
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
    <div class="mb-3 position-relative">
        <label>Kabupaten</label>
        <input type="text" class="form-control" placeholder="Ketik kabupaten..." wire:model.live="searchKabupaten">
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
    <div class="mb-3 position-relative">
        <label>Kecamatan</label>
        <input type="text" class="form-control" placeholder="Ketik kecamatan..." wire:model.live="searchKecamatan">
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
    <div class="mb-3 position-relative">
        <label>Kelurahan</label>
        <input type="text" class="form-control" placeholder="Ketik kelurahan..." wire:model.live="searchKelurahan">
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
                <textarea class="form-control" wire:model.live="alamat"></textarea>
                <div class="form-text">Sesuai KTP.</div>
                @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



            <hr>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" wire:model.live="email" disabled>
                <div class="form-text">Email yang masih aktif.</div>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input class="form-control" type="number" wire:model.live="no_hp">
                <div class="form-text">No HP yang masih aktif.</div>
                @error('no_hp') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <hr>
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <div class="d-grid gap-2">
                <button class="btn btn-primary" wire:click="save">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('ocr-success', event => {
    alert(event.detail.message);
});
</script>