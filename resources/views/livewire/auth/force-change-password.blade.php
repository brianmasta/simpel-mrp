<div>
<div class="container py-5" style="max-width:450px">

<div class="card shadow">

<div class="card-header bg-warning text-dark">
<h5 class="mb-0">
<i class="cil-lock-locked me-2"></i>
Ganti Password
</h5>
</div>

<div class="card-body">

<div class="alert alert-warning">
Untuk keamanan akun Anda, silakan buat password baru sebelum melanjutkan.
</div>

<form wire:submit.prevent="simpan">

<div class="mb-3">
<label>Password Baru</label>

<div class="input-group">

<input 
type="{{ $showPassword ? 'text' : 'password' }}"
class="form-control"
wire:model="password">

<button 
type="button"
class="btn btn-outline-secondary"
wire:click="togglePassword">

@if($showPassword)
<i class="cil-lock-unlocked"></i>
@else
<i class="cil-lock-locked"></i>
@endif

</button>

</div>

@error('password')
<small class="text-danger">{{ $message }}</small>
@enderror

</div>


<div class="mb-3">

<label>Konfirmasi Password</label>

<input 
type="{{ $showPassword ? 'text' : 'password' }}"
class="form-control"
wire:model="password_confirmation">

</div>


<button class="btn btn-primary w-100">

<i class="cil-save me-1"></i>
Simpan Password

</button>

</form>

</div>
</div>
</div>
</div>
