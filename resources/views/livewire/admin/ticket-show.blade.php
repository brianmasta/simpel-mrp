<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <strong>🎫 Detail Tiket Kendala</strong>
        </div>

        <div class="card-body">
            <p><strong>No Tiket:</strong> {{ $ticket->ticket_number }}</p>
            <p><strong>Nama:</strong> {{ $ticket->name }}</p>
            <p><strong>Email:</strong> {{ $ticket->email ?? '-' }}</p>
            <p><strong>Subjek:</strong> {{ $ticket->subject }}</p>

            <hr>

            <p><strong>Deskripsi Kendala:</strong></p>
            <pre class="bg-light p-3">{{ $ticket->description }}</pre>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                    <select wire:model="status" class="form-select">
                        <option value="open">Open</option>
                        <option value="progress">Dalam Proses</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>

            <button wire:click="updateStatus"
                    class="btn btn-success mt-3">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>
