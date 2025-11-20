<?php

namespace App\Livewire\Admin\Technicians;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $technicians = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadTechnicians();
    }

    public function loadTechnicians(): void
    {
        $this->technicians = User::where('role', User::ROLE_TECHNICIAN)->get();
    }

    public function delete(int $id): void
    {
        $tech = User::where('role', User::ROLE_TECHNICIAN)->findOrFail($id);
        $tech->delete();
        $this->loadTechnicians();
    }

    public function render()
    {
        return view('livewire.admin.technicians.index');
    }
}
