<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <strong>📨 Live Chat Bantuan</strong>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($chats as $chat)
                    <tr>
                        <td>{{ $chat->name }}</td>
                        <td>{{ $chat->email ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $chat->status === 'open' ? 'success' : 'secondary' }}">
                                {{ strtoupper($chat->status) }}
                            </span>
                        </td>
                        <td>{{ $chat->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.livechat.show', $chat->id) }}"
                               wire:navigate
                               class="btn btn-sm btn-primary">
                                Buka Chat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada chat masuk
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
