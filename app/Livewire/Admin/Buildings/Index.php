<?php

namespace App\Livewire\Admin\Buildings;

use App\Models\Building;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $buildings = [];
    public $showDeleteModal = false;
    public $deleteId = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadBuildings();
    }

    public function loadBuildings(): void
    {
        $this->buildings = Building::withCount(['computerLabs', 'pcs', 'accessories', 'networkDevices'])
            ->orderBy('name')
            ->get();
    }

    public function confirmDelete($id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            Building::findOrFail($this->deleteId)->delete();
            $this->deleteId = 0;
            $this->showDeleteModal = false;
            $this->loadBuildings();
        }
    }

    public function render()
    {
        return view('livewire.admin.buildings.index');
    }
}
