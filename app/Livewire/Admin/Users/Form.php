<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Form extends Component
{
    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function mount($id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        if ($id) {
            $this->userId = $id;
            $editUser = User::findOrFail($id);
            $this->name = $editUser->name;
            $this->email = $editUser->email;
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($this->userId ? ',' . $this->userId : ''),
        ];

        if (!$this->userId || $this->password) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => User::ROLE_USER,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
        } else {
            User::create($data);
        }

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.form');
    }
}
