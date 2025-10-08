<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public $name, $email, $role, $password;
    public $search = '';
    public $userId;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:admin,petugas,pengguna',
        'password' => 'required|min:6',
    ];
    
    public function render()
    {
        $users = User::where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->orderBy('role')
            ->paginate(10);

        return view('livewire.admin.manage-users', ['users' => $users]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'role', 'password', 'userId', 'isEditing']);
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('success', 'Akun berhasil ditambahkan.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->isEditing = true;
    }

    public function update()
    {
        $user = User::findOrFail($this->userId);
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,pengguna',
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ]);

        session()->flash('success', 'Akun berhasil diperbarui.');
        $this->resetForm();
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make('password123')]);

        session()->flash('success', 'Password berhasil direset ke: password123');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Akun berhasil dihapus.');
    }
}
