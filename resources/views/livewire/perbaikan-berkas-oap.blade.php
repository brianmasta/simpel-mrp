<div>
<div class="container-fluid px-4 py-3">

    <h4 class="fw-bold mb-3">
        <i class="cil-file"></i> Perbaikan Berkas Surat OAP
    </h4>

    <div class="alert alert-warning">
        ⚠️ Beberapa berkas perlu diperbaiki sesuai catatan petugas.
        Silakan unggah ulang berkas yang diminta.
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-semibold">
            Daftar Berkas Perlu Perbaikan
        </div>

        <div class="card-body">

            <form wire:submit.prevent="submit">

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Dokumen</th>
                            <th>Catatan Petugas</th>
                            <th>Upload Perbaikan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($perluPerbaikan as $v)
                        <tr>
                            <td class="fw-semibold text-uppercase">
                                {{ $v->dokumen }}
                            </td>

                            <td>
                                <span class="text-danger">
                                    {{ $v->catatan ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <input type="file"
                                    wire:model="upload.{{ $v->dokumen }}"
                                    class="form-control"
                                    accept="image/*,application/pdf">

                                <div wire:loading
                                    wire:target="upload.{{ $v->dokumen }}"
                                    class="small text-primary mt-1">
                                    Mengunggah...
                                </div>

                                @error("upload.$v->dokumen")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-grid">
                    <button class="btn btn-primary"
                        wire:loading.attr="disabled">
                        Kirim Perbaikan Berkas
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
</div>
