<?php

namespace App\Livewire\Admin;

use App\Models\Ticket;
use Livewire\Component;

class TicketIndex extends Component
{
    public $status = 'all';

    public function render()
    {
        $tickets = Ticket::when($this->status !== 'all', function ($q) {
                $q->where('status', $this->status);
            })
            ->latest()
            ->get();

        return view('livewire.admin.ticket-index', [
            'tickets' => $tickets
        ])->layout('components.layouts.app');
    }
}
