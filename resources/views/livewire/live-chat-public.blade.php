<div>

@if(!$chat)
    {{-- ================= FORM MULAI CHAT ================= --}}
    <input
        wire:model="name"
        placeholder="Nama"
        class="form-control mb-2">

    <input
        wire:model="email"
        placeholder="Email (opsional)"
        class="form-control mb-2">

    <button
        wire:click="startChat"
        class="btn btn-primary w-100">
        Mulai Chat
    </button>

@else
    {{-- ================= HEADER STATUS ================= --}}
    <div class="mb-2 text-center">
        <span class="badge
            @if($chat->status === 'open') bg-success
            @elseif($chat->status === 'need_email') bg-warning
            @else bg-secondary
            @endif
        ">
            {{ strtoupper($chat->status) }}
        </span>
    </div>

    {{-- ================= AREA CHAT ================= --}}
    <div wire:poll.5s
         class="border rounded p-2 mb-2"
         style="height:260px; overflow-y:auto; background:#f8f9fa;">

        @forelse($messages as $m)

            {{-- ===== PESAN SISTEM ===== --}}
            @if($m->sender === 'system')
                <div class="text-center my-2">
                    <span class="badge bg-info">
                        🔔 {{ $m->message }}
                    </span>
                </div>

            {{-- ===== PESAN PENGGUNA ===== --}}
            @elseif($m->sender === 'user')
                <div class="mb-1">
                    <strong>Anda:</strong>
                    {{ $m->message }}
                </div>

            {{-- ===== PESAN PETUGAS ===== --}}
            @else
                <div class="mb-1">
                    <strong>Petugas:</strong>
                    {{ $m->message }}
                </div>
            @endif

        @empty
            <div class="text-muted text-center">
                Belum ada pesan
            </div>
        @endforelse
    </div>

    {{-- ================= STATUS OPEN ================= --}}
    @if($chat->status === 'open')
        <textarea
            wire:model.defer="message"
            class="form-control mt-2"
            placeholder="Tulis pesan Anda..."></textarea>

        <button
            wire:click="send"
            class="btn btn-success w-100 mt-2">
            Kirim
        </button>

    {{-- ================= STATUS NEED EMAIL ================= --}}
    @elseif($chat->status === 'need_email')
        <div class="alert alert-warning text-center mt-2">
            ⚠️ Untuk melanjutkan penanganan,
            silakan masukkan <strong>alamat email aktif</strong>.
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

    {{-- ================= STATUS CLOSED ================= --}}
    @else
        <div class="alert alert-success text-center mt-2">

            {{-- CLOSED: SELESAI VIA CHAT --}}
            @if($chat->closed_reason === 'chat_resolved')
                ✅ <strong>Permasalahan Anda telah berhasil ditangani</strong><br>
                melalui layanan live chat SIMPEL-MRP.

            {{-- CLOSED: DIKONVERSI MENJADI TIKET --}}
            @elseif($chat->closed_reason === 'converted_to_ticket')
                🧾 <strong>Tiket berhasil dibuat</strong><br>

                @if($chat->ticket)
                    Nomor Tiket:
                    <strong class="d-block my-1">
                        {{ $chat->ticket->ticket_number }}
                    </strong>
                @endif

                Silakan <strong>periksa email Anda</strong>
                untuk memantau perkembangan tiket.
            @endif

            <hr class="my-2">
            Percakapan ini telah ditutup oleh petugas SIMPEL-MRP.
        </div>

        <a href="/"
           class="btn btn-outline-primary w-100 mt-2">
            Kembali ke Beranda
        </a>
    @endif
@endif

</div>
