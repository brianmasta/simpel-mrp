<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog as ModelsActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.admin.activity-log', [
            'logs' => ModelsActivityLog::with('user')
                ->latest()
                ->paginate(15),
        ])->layout('components.layouts.app');
    }
}
