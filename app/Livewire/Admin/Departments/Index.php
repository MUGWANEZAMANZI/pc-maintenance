<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $departments = [];
    public $showDeleteModal = false;
    public $deleteDepartmentId = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadDepartments();
    }

    public function loadDepartments(): void
    {
        $this->departments = Department::withCount('computerLabs')->orderBy('name')->get();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteDepartmentId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteDepartmentId) {
            $department = Department::findOrFail($this->deleteDepartmentId);
            $department->delete();
            $this->deleteDepartmentId = 0;
            $this->showDeleteModal = false;
            $this->loadDepartments();
        }
    }

    public function render()
    {
        return view('livewire.admin.departments.index');
    }
}
