<div>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>🎫 Dashboard Tiket Kendala</strong>

            <select wire:model="status" class="form-select w-auto">
                <option value="all">Semua</option>
                <option value="open">Open</option>
                <option value="progress">Dalam Proses</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No Tiket</th>
                        <th>Nama</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->name }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $ticket->status === 'open' ? 'danger' :
                                ($ticket->status === 'progress' ? 'warning' : 'success')
                            }}">
                                {{ strtoupper($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                               wire:navigate
                               class="btn btn-sm btn-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Tidak ada tiket kendala
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
