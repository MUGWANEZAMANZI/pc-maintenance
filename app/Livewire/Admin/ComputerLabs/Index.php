<?php

namespace App\Livewire\Admin\ComputerLabs;

use App\Models\ComputerLab;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $labs = [];
    public $showDeleteModal = false;
    public $deleteLabId = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadLabs();
    }

    public function loadLabs(): void
    {
        $this->labs = ComputerLab::with('department')->withCount('pcs')->orderBy('name')->get();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteLabId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteLabId) {
            $lab = ComputerLab::findOrFail($this->deleteLabId);
            $lab->delete();
            $this->deleteLabId = 0;
            $this->showDeleteModal = false;
            $this->loadLabs();
        }
    }

    public function render()
    {
        return view('livewire.admin.computer-labs.index');
    }
}
