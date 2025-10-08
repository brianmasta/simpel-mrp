<div>
    <div class="card mb-4">
        <div class="card-header"><strong>Format Surat</strong></div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-3">
                <label class="form-label">Jenis Surat</label>
                <input class="form-control" type="text" wire:model.defer="jenis">
                @error('jenis') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div wire:ignore class="mb-3">
                <label class="form-label">Isi Format Surat</label>
                {{-- <div id="editor"></div> --}}
                <div id="summernote">Hello Summernote</div>
                @error('isi') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" wire:click="simpan">
                    {{ $formatId ? 'Perbarui Format' : 'Simpan Format' }}
                </button>
                @if($formatId)
                    <button type="button" class="btn btn-secondary" wire:click="resetForm" wire:click="$dispatch('reset-editor')">
                        Batal Edit
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Daftar format --}}
    <div class="card mb-4">
        <div class="card-header"><strong>Daftar Format Surat</strong></div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarFormat as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" wire:click="preview({{ $item->id }})">Preview</button>
                                <button class="btn btn-sm btn-warning" wire:click="edit({{ $item->id }})">Edit</button>
                                <button class="btn btn-sm btn-danger" wire:click="hapus({{ $item->id }})"
                                    onclick="return confirm('Yakin ingin menghapus format surat ini?')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada format surat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Preview --}}
@if($showPdfModal)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl" style="max-width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview PDF</h5>
                    <button type="button" class="btn-close" wire:click="closePdfModal"></button>
                </div>
                <div class="modal-body">
                    <iframe 
                        src="data:application/pdf;base64,{{ $pdfContent }}" 
                        type="application/pdf" 
                        width="100%" 
                        height="600px">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endif
</div>

@script
<script>
const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike','code'],
    ['table'], // Tambahkan 'table' di sini
    ['blockquote', 'code-block'],
    ['link', 'image'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'align': [] }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    ['clean'],
    [{ 'script': 'sub'}, { 'script': 'super' }],     // superscript/subscript
];


const quill = new Quill('#editor', {
    modules: { toolbar: toolbarOptions,clipboard: {
            matchVisual: false, // memastikan HTML asli tetap utuh
        } },
    theme: 'snow',
    placeholder: 'Tulis isi format surat...'
});

quill.on('text-change', function() {
    @this.set('isi', quill.root.innerHTML);
});

Livewire.on('reset-editor', () => {
    quill.root.innerHTML = '';
});

Livewire.on('set-editor', (isi) => {
    quill.root.innerHTML = isi || '';
});
</script>
@endscript


@script
<script>
    // Inisialisasi Summernote
    $('#summernote').summernote({
          toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['height', ['height']],
    ['table', ['table']],
  ],
        height: 200,
        callbacks: {
            onChange: function(contents, $editable) {
                @this.set('isi', contents); // update properti Livewire
            }
        }
    });

    // Event reset editor
    Livewire.on('reset-editor', () => {
        $('#summernote').summernote('reset');
    });

    // Event set isi editor (misal saat edit)
    Livewire.on('set-editor', isi => {
        $('#summernote').summernote('code', isi);
    });
</script>
@endscript
