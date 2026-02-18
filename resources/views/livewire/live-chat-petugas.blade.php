<div>
<div class="container-fluid" wire:poll.3s>
    <div class="card shadow-sm">
        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Live Chat Bantuan</strong><br>
                <small class="text-muted">
                    {{ $chat->name }} | {{ $chat->email ?? '-' }}
                </small>
            </div>

            <span class="badge bg-{{ $chat->status === 'open' ? 'success' : 'secondary' }}">
                {{ strtoupper($chat->status) }}
            </span>
        </div>

        <!-- BODY CHAT -->
        <div class="card-body"
             style="height:400px; overflow-y:auto; background:#f8f9fa;">

            @forelse($messages as $msg)
                @if($msg->sender === 'petugas')
                    <!-- PESAN PETUGAS -->
                    <div class="d-flex justify-content-end mb-2">
                        <div class="bg-primary text-white p-2 rounded"
                             style="max-width:70%;">
                            <small class="fw-bold">Petugas</small><br>
                            {{ $msg->message }}<br>
                            <small class="opacity-75">
                                {{ $msg->created_at->format('H:i') }}
                            </small>
                        </div>
                    </div>
                @else
                    <!-- PESAN MASYARAKAT -->
                    <div class="d-flex justify-content-start mb-2">
                        <div class="bg-white border p-2 rounded"
                             style="max-width:70%;">
                            <small class="fw-bold">Masyarakat</small><br>
                            {{ $msg->message }}<br>
                            <small class="text-muted">
                                {{ $msg->created_at->format('H:i') }}
                            </small>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center text-muted mt-4">
                    Belum ada pesan
                </div>
            @endforelse
        </div>

        <!-- FOOTER / INPUT -->
        @if($chat->status === 'open')
            <div class="card-footer">
                <textarea wire:model.defer="reply"
                          class="form-control mb-2"
                          placeholder="Tulis balasan kepada masyarakat..."></textarea>

                <div class="d-flex gap-2">
                    <button wire:click="sendReply"
                            class="btn btn-primary">
                        Kirim Balasan
                    </button>

                    {{-- opsional jika sudah Anda buat --}}
                    {{-- 
                    <button wire:click="convertToTicket"
                            class="btn btn-warning">
                        Jadikan Tiket Kendala
                    </button>

                    <button wire:click="closeChat"
                            class="btn btn-secondary">
                        Tutup Chat
                    </button>
                    --}}
                </div>

                <small class="text-muted d-block mt-2">
                    ⚠ Gunakan bahasa sopan & profesional.  
                    Semua percakapan tercatat dalam sistem.
                </small>
            </div>
        @else
            <div class="card-footer bg-light text-center text-muted">
                Chat telah ditutup
            </div>
        @endif
    </div>
</div>

</div>
