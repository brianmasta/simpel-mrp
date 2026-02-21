<div>
<div class="container-fluid px-4 py-3">

    <h4 class="mb-3 fw-bold">
        <i class="cil-task"></i> Verifikasi Berkas Surat OAP
    </h4>

    <div class="row">

        {{-- LIST PENGAJUAN --}}
        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">
                    Daftar Pengajuan
                </div>

<div class="list-group list-group-flush">
    @foreach($pengajuan as $p)
        <div class="list-group-item d-flex justify-content-between align-items-center">

            <div>
                <div class="fw-semibold">{{ $p->user->name }}</div>
                <small class="text-muted">
                    {{ $p->created_at->format('d M Y') }}
                </small>

                {{-- BADGE STATUS --}}
                <div class="mt-1">
                    <span class="badge 
                        bg-{{ 
                            $p->status === 'terbit' ? 'success' : 
                            ($p->status === 'perlu_perbaikan' ? 'danger' : 
                            ($p->status === 'verifikasi' ? 'warning' : 'secondary'))
                        }}">
                        {{ strtoupper($p->status ?? 'MENUNGGU') }}
                    </span>
                </div>
            </div>

            <button 
                wire:click="pilih({{ $p->id }})"
                class="btn btn-sm btn-primary">
                Buka
            </button>
        </div>
    @endforeach
</div>
            </div>
        </div>

        {{-- DETAIL --}}
        <div class="col-lg-8">
            @if($selectedId)

                @php
                    $detail = \App\Models\PengajuanSurat::find($selectedId);
                    $verifikasi = \App\Models\VerifikasiPengajuan::where('pengajuan_id',$selectedId)->get();
                @endphp

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white fw-semibold">
                        Detail Verifikasi
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama:</strong> {{ $detail->user->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Alasan:</strong> {{ $detail->alasan }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th>Dokumen</th>
                                        <th>Preview</th>
                                        <th>Status</th>
                                        <th width="35%">Catatan Petugas</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($verifikasi as $v)
                                        <tr>
                                            <td class="fw-semibold text-uppercase">
                                                {{ $v->dokumen }}
                                            </td>

                                            <td>
                                                @php
                                                    $file = $selectedData?->{$v->dokumen};
                                                @endphp

                                                @if($file)
                                                    <a href="{{ route('view.private', [
                                                        'folder' => $v->dokumen,
                                                        'filename' => basename($file)
                                                    ]) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button 
                                                        wire:click="setStatus({{ $v->id }},'valid')"
                                                        class="btn btn-success">
                                                        Valid
                                                    </button>

                                                    <button 
                                                        wire:click="setStatus({{ $v->id }},'perlu_perbaikan')"
                                                        class="btn btn-danger">
                                                        Perbaiki
                                                    </button>
                                                </div>

                                                <div class="mt-1">
                                                    @if($v->status == 'valid')
                                                        <span class="badge bg-success">Valid</span>
                                                    @elseif($v->status == 'perlu_perbaikan')
                                                        <span class="badge bg-danger">Perlu Perbaikan</span>
                                                    @else
                                                        <span class="badge bg-secondary">Menunggu</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                <textarea 
                                                    wire:model.defer="catatan.{{ $v->dokumen }}"
                                                    rows="2"
                                                    class="form-control form-control-sm"
                                                    placeholder="Isi catatan..."></textarea>

                                                <button 
                                                    wire:click="simpanCatatan({{ $v->id }},'{{ $v->dokumen }}')"
                                                    class="btn btn-outline-secondary btn-sm mt-1">
                                                    Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

            @else
                <div class="alert alert-info">
                    Pilih pengajuan untuk diverifikasi.
                </div>
            @endif
        </div>

    </div>
</div>
</div>
