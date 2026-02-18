<?php

namespace App\Livewire;

use App\Models\LiveChat;
use App\Models\Ticket;
use App\Notifications\ChatClosedNotification;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class LiveChatPetugas extends Component
{
    public $chat;
    public $reply;

    public $emailInput;

    public function mount($chatId)
    {
        $this->chat = LiveChat::findOrFail($chatId);
    }

    public function sendReply()
    {
        $this->chat->messages()->create([
            'sender' => 'petugas',
            'sender_id' => auth()->id(),
            'message' => $this->reply
        ]);

        $this->reply = '';
    }

    
    public function convertToTicket()
    {
        // 🔒 CEGAH KONVERSI GANDA
        if ($this->chat->status === 'closed') {
            $this->dispatch('toast', [
                'type' => 'warning',
                'message' => 'Chat ini sudah ditutup atau sudah dikonversi menjadi tiket.'
            ]);
            return;
        }

        
        // ❗ JIKA EMAIL BELUM ADA → MINTA EMAIL
        if (!$this->chat->email) {
            $this->chat->update([
                'status' => 'need_email'
            ]);

            $this->dispatch('toast', [
                'type' => 'info',
                'message' => 'Email pengguna belum tersedia. Sistem meminta email terlebih dahulu.'
            ]);

            return;
        }

        // Gabungkan seluruh percakapan
        $conversation = $this->chat->messages()
            ->orderBy('created_at')
            ->get()
            ->map(function ($msg) {
                return strtoupper($msg->sender) . ': ' . $msg->message;
            })
            ->implode("\n");

        // Buat tiket
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-' . now()->format('Ymd-His'),
            'name' => $this->chat->name,
            'email' => $this->chat->email,
            'subject' => 'Kendala dari Live Chat',
            'description' => $conversation,
            'created_from_chat_id' => $this->chat->id,
            'handled_by' => auth()->id(),
        ]);

        // 🔑 TUTUP CHAT (ALASAN: DIBUAT TIKET)
        $this->chat->update([
            'status' => 'closed',
            'closed_reason' => 'converted_to_ticket',
            'closed_at' => now(),
        ]);

        // Notifikasi ke pengguna
        if ($this->chat->email) {
            Notification::route('mail', $this->chat->email)
                ->notify(new TicketCreatedNotification($ticket));
        }

        // Notifikasi sukses
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Chat berhasil dikonversi menjadi tiket: ' . $ticket->ticket_number
        ]);
    }

    public function closeChat()
    {
        if ($this->chat->status === 'closed') {
            $this->dispatch('toast', [
                'type' => 'info',
                'message' => 'Chat ini sudah ditutup.'
            ]);
            return;
        }

        $this->chat->update([
            'status' => 'closed'
        ]);

        // Kirim notifikasi ke pengguna
        if ($this->chat->email) {
            Notification::route('mail', $this->chat->email)
                ->notify(new ChatClosedNotification($this->chat));
        }

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Chat berhasil ditutup. Masalah telah ditangani.'
        ]);
    }

    public function closeChatResolved()
    {
        if ($this->chat->status === 'closed') {
            return;
        }

        $this->chat->update([
            'status' => 'closed',
            'closed_reason' => 'chat_resolved',
            'closed_at' => now(),
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Chat ditutup. Masalah berhasil ditangani melalui chat.'
        ]);
    }

    public function render()
    {
        return view('livewire.live-chat-petugas', [
            'messages' => $this->chat->messages()->latest()->get()
        ])->layout('components.layouts.app'); // ⬅️ PENTING
    }
}
