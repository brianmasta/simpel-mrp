<div>
    <div class="card">
        <div class="card-header fw-bold">
            Log Aktivitas Akun
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Aktivitas</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $i => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $i }}</td>
                        <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $log->user->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ strtoupper($log->role ?? '-') }}
                            </span>
                        </td>
                        <td>{{ $log->activity }}</td>
                        <td title="{{ $log->user_agent }}">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada aktivitas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $logs->links() }}
        </div>
    </div>
</div>
