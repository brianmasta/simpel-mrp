<div>
<div class="container-fluid" wire:poll.3s>
    <div class="card shadow-sm">

        {{-- ================= HEADER ================= --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Live Chat Bantuan</strong><br>
                <small class="text-muted">
                    {{ $chat->name }} | {{ $chat->email ?? 'Email belum tersedia' }}
                </small>
            </div>

            <span class="badge
                @if($chat->status === 'open') bg-success
                @elseif($chat->status === 'need_email') bg-warning
                @else bg-secondary
                @endif
            ">
                {{ strtoupper($chat->status) }}
            </span>
        </div>

        {{-- ================= BODY CHAT ================= --}}
        <div class="card-body"
             style="height:400px; overflow-y:auto; background:#f8f9fa;">

            @forelse($messages as $msg)
                @if($msg->sender === 'petugas')
                    {{-- PESAN PETUGAS --}}
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
                    {{-- PESAN MASYARAKAT --}}
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

        {{-- ================= FOOTER / ACTION ================= --}}
        @if($chat->status === 'open')

            <div class="card-footer">
                <textarea wire:model.defer="reply"
                          class="form-control mb-2"
                          placeholder="Tulis balasan kepada masyarakat..."></textarea>

                <div class="d-flex flex-wrap gap-2">
                    <button wire:click="sendReply"
                            class="btn btn-primary">
                        Kirim Balasan
                    </button>

                    <button wire:click="convertToTicket"
                            class="btn btn-warning">
                        Jadikan Tiket Kendala
                    </button>

                    <button wire:click="closeChatResolved"
                            class="btn btn-secondary">
                        Tutup Chat (Selesai)
                    </button>
                </div>

                <small class="text-muted d-block mt-2">
                    ⚠ Gunakan bahasa sopan & profesional.  
                    Semua percakapan tercatat dalam sistem SIMPEL-MRP.
                </small>
            </div>

        @else
            {{-- ================= CHAT CLOSED ================= --}}
            <div class="card-footer">
                <div class="alert alert-success mb-0">

                    {{-- CLOSED VIA CHAT --}}
                    @if($chat->closed_reason === 'chat_resolved')
                        ✅ <strong>Chat ditutup</strong><br>
                        Permasalahan masyarakat telah berhasil ditangani
                        langsung melalui live chat SIMPEL-MRP.

                    {{-- CLOSED VIA TICKET --}}
                    @elseif($chat->closed_reason === 'converted_to_ticket')
                        🧾 <strong>Chat dikonversi menjadi tiket</strong><br>

                        @if($chat->ticket)
                            Nomor Tiket:
                            <strong>{{ $chat->ticket->ticket_number }}</strong><br>
                        @endif

                        Penanganan lanjutan dilakukan melalui sistem tiket.
                    @endif

                    <hr class="my-2">
                    <small class="text-muted">
                        Chat bersifat <strong>read-only</strong> dan tidak dapat dibalas kembali.
                    </small>
                </div>
            </div>
        @endif

    </div>
</div>
</div>
