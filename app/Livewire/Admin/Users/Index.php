<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    public $users = [];
    public $showDeleteModal = false;
    public $deleteUserId = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadUsers();
    }

    public function loadUsers(): void
    {
        $this->users = User::where('role', User::ROLE_USER)->orderBy('name')->get();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteUserId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteUserId) {
            $user = User::findOrFail($this->deleteUserId);
            $user->delete();
            $this->deleteUserId = 0;
            $this->showDeleteModal = false;
            $this->loadUsers();
        }
    }

    public function render()
    {
        return view('livewire.admin.users.index');
    }
}
