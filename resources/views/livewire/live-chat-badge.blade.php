<div>
    <span wire:poll.5s>
        @if($totalOpen > 0)
            <span class="badge bg-danger ms-auto badgechat">
                {{ $totalOpen }}
            </span>
        @endif
    </span>
</div>
