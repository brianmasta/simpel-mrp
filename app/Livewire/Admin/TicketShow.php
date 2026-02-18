<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use Livewire\Component;

class TicketShow extends Component
{
    public $ticket;
    public $status;

    public function mount($ticket)
    {
        $this->ticket = Ticket::findOrFail($ticket);
        $this->status = $this->ticket->status;
    }

    public function updateStatus()
    {
        $this->ticket->update([
            'status' => $this->status
        ]);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Status tiket berhasil diperbarui'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.ticket-show')
            ->layout('components.layouts.app');
    }
}
