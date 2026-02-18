<div class="live-chat-container">

@if(!$chat)
    {{-- ================= FORM MULAI CHAT ================= --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="text-center mb-3 fw-bold">
                💬 Live Chat Bantuan SIMPEL-MRP
            </h6>

            <input
                wire:model="name"
                placeholder="Nama Anda"
                class="form-control mb-2">

            <input
                wire:model="email"
                placeholder="Email (opsional)"
                class="form-control mb-3">

            <button
                wire:click="startChat"
                class="btn btn-primary w-100">
                Mulai Chat
            </button>
        </div>
    </div>

@else
    {{-- ================= HEADER ================= --}}
    <div class="chat-header d-flex justify-content-between align-items-center mb-2">
        <div>
            <strong>Live Chat SIMPEL-MRP</strong><br>
            <small class="text-muted">
                {{ $chat->name }}
            </small>
        </div>

        <span class="badge
            @if($chat->status === 'open') bg-success
            @elseif($chat->status === 'need_email') bg-warning
            @else bg-secondary
            @endif">
            {{ strtoupper($chat->status) }}
        </span>
    </div>

    {{-- ================= AREA CHAT ================= --}}
    <div wire:poll.5s
         class="chat-body shadow-sm rounded p-3 mb-2">

        @forelse($messages as $m)

            {{-- SYSTEM MESSAGE --}}
            @if($m->sender === 'system')
                <div class="text-center my-2">
                    <span class="system-chip">
                        🔔 {{ $m->message }}
                    </span>
                </div>

            {{-- USER MESSAGE --}}
            @elseif($m->sender === 'user')
                <div class="d-flex justify-content-end mb-2">
                    <div class="chat-bubble user">
                        {{ $m->message }}
                        <div class="chat-time">
                            {{ $m->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>

            {{-- PETUGAS MESSAGE --}}
            @else
                <div class="d-flex justify-content-start mb-2">
                    <div class="chat-bubble petugas">
                        {{ $m->message }}
                        <div class="chat-time">
                            {{ $m->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endif

        @empty
            <div class="text-muted text-center">
                Belum ada pesan
            </div>
        @endforelse
    </div>

    {{-- ================= INPUT ================= --}}
    @if($chat->status === 'open')
        <div class="chat-input">
            <textarea
                wire:model.defer="message"
                class="form-control"
                rows="2"
                placeholder="Tulis pesan..."></textarea>

            <button
                wire:click="send"
                class="btn btn-success mt-2 w-100">
                Kirim
            </button>
        </div>

    {{-- ================= NEED EMAIL ================= --}}
    @elseif($chat->status === 'need_email')
        <div class="alert alert-warning text-center">
            Untuk melanjutkan penanganan,
            silakan masukkan <strong>email aktif</strong>.
        </div>

        <input
            type="email"
            wire:model="email"
            class="form-control mb-2"
            placeholder="Alamat Email Aktif">

        <button
            wire:click="submitEmail"
            class="btn btn-primary w-100">
            Simpan Email
        </button>

    {{-- ================= CLOSED ================= --}}
    @else
        <div class="alert alert-success text-center mt-2">

            @if($chat->closed_reason === 'chat_resolved')
                ✅ <strong>Permasalahan Anda telah diselesaikan</strong><br>
                melalui layanan live chat.

            @elseif($chat->closed_reason === 'converted_to_ticket')
                🧾 <strong>Tiket berhasil dibuat</strong><br>

                @if($chat->ticket)
                    <span class="d-block my-1">
                        Nomor Tiket:
                        <strong>{{ $chat->ticket->ticket_number }}</strong>
                    </span>
                @endif

                Silakan periksa email Anda
                untuk memantau perkembangan tiket.
            @endif

            <hr>
            Percakapan ini telah ditutup.
        </div>

        <a href="/"
           class="btn btn-outline-primary w-100">
            Kembali ke Beranda
        </a>
    @endif
@endif

</div>
