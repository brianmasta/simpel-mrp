<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public $name, $email, $role, $password;
    public $search = '';
    public $userId;
    public $isEditing = false;
    public $user;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,petugas,pengguna',
        'password' => 'required|min:6',
    ];

    // Rate-limit untuk reset password
    protected $resetAttempts = 5;

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        $users = User::where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('role')
            ->paginate(10);

        return view('livewire.admin.manage-users', [
            'users' => $users,
            'searchSafe' => e($this->search), // XSS safe
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'role', 'password', 'userId', 'isEditing']);
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        Log::info("Admin {$this->user->id} created user {$user->id}");

        session()->flash('success', 'Akun berhasil ditambahkan.');
        $this->resetForm();
    }

    public function edit($id)
    {
        Gate::authorize('admin');

        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEditing = true;
    }

    public function update()
    {
        Gate::authorize('admin');

        $user = User::findOrFail($this->userId);
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:admin,petugas,pengguna',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ]);

        Log::info("Admin {$this->user->id} updated user {$user->id}");

        session()->flash('success', 'Akun berhasil diperbarui.');
        $this->resetForm();
    }

    public function resetPassword($id)
    {
        Gate::authorize('admin');

        $attempts = session()->get('reset_attempts', 0);
        if ($attempts >= $this->resetAttempts) {
            session()->flash('error', 'Batas reset password tercapai.');
            return;
        }

        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make('password123')]);

        session()->put('reset_attempts', $attempts + 1);
        Log::warning("Admin {$this->user->id} reset password for user {$user->id}");

        session()->flash('success', 'Password berhasil direset ke: password123');
    }

    public function delete($id)
    {
        Gate::authorize('admin');

        User::findOrFail($id)->delete();
        Log::warning("Admin {$this->user->id} deleted user {$id}");
        session()->flash('success', 'Akun berhasil dihapus.');
    }
}
