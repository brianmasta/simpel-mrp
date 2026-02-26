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

                        <div class="mt-3">
                            <button
                                wire:click="lihatDokumen({{ $surat->id }})"
                                class="btn btn-primary">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Lihat Dokumen Asli
                            </button>
                        </div>
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
</div>

</div>
