<div>
@if(!$chat)
<input wire:model="name" placeholder="Nama" class="form-control mb-2">
<input wire:model="email" placeholder="Email (opsional)" class="form-control mb-2">
<button wire:click="startChat" class="btn btn-primary w-100">Mulai Chat</button>
@else
<div wire:poll.5s>
@foreach($messages as $m)
  <div><b>{{ $m->sender }}:</b> {{ $m->message }}</div>
@endforeach
</div>

<textarea wire:model.defer="message" class="form-control mt-2"></textarea>
<button wire:click="send" class="btn btn-success w-100 mt-2">Kirim</button>
@endif
</div>
