<?php

namespace App\Livewire\Admin\Technicians;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Form extends Component
{
    public ?User $technician = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?string $availability_status = null;

    public function mount(?int $id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        if ($id) {
            $this->technician = User::where('role', User::ROLE_TECHNICIAN)->findOrFail($id);
            $this->name = $this->technician->name;
            $this->email = $this->technician->email;
            $this->availability_status = $this->technician->availability_status;
        }
    }

    public function save()
    {
        $data = $this->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'password' => $this->technician ? 'nullable|min:6' : 'required|min:6',
            'availability_status' => 'nullable|string'
        ]);

        if ($this->technician) {
            if ($data['password']) {
                $this->technician->password = bcrypt($data['password']);
            }
            $this->technician->name = $data['name'];
            $this->technician->email = $data['email'];
            $this->technician->availability_status = $data['availability_status'];
            $this->technician->save();
        } else {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => User::ROLE_TECHNICIAN,
                'availability_status' => $data['availability_status']
            ]);
        }
        return redirect('/admin/technicians');
    }

    public function render()
    {
        return view('livewire.admin.technicians.form');
    }
}
