<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Form extends Component
{
    public $departmentId;
    public $name = '';
    public $code = '';
    public $description = '';
    public $location = '';

    public function mount($id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        if ($id) {
            $this->departmentId = $id;
            $department = Department::findOrFail($id);
            $this->name = $department->name;
            $this->code = $department->code;
            $this->description = $department->description ?? '';
            $this->location = $department->location ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code' . ($this->departmentId ? ',' . $this->departmentId : ''),
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'location' => $this->location,
        ];

        if ($this->departmentId) {
            $department = Department::findOrFail($this->departmentId);
            $department->update($data);
        } else {
            Department::create($data);
        }

        return redirect()->route('admin.departments.index');
    }

    public function render()
    {
        return view('livewire.admin.departments.form');
    }
}
