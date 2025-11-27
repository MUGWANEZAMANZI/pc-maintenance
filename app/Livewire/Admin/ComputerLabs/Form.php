<?php

namespace App\Livewire\Admin\ComputerLabs;

use App\Models\ComputerLab;
use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Form extends Component
{
    public $labId;
    public $name = '';
    public $code = '';
    public $department_id = '';
    public $location = '';
    public $capacity = '';
    public $description = '';
    public $departments = [];

    public function mount($id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        $this->departments = Department::orderBy('name')->get();

        if ($id) {
            $this->labId = $id;
            $lab = ComputerLab::findOrFail($id);
            $this->name = $lab->name;
            $this->code = $lab->code;
            $this->department_id = $lab->department_id;
            $this->location = $lab->location ?? '';
            $this->capacity = $lab->capacity ?? '';
            $this->description = $lab->description ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:computer_labs,code' . ($this->labId ? ',' . $this->labId : ''),
            'department_id' => 'required|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'department_id' => $this->department_id,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'description' => $this->description,
        ];

        if ($this->labId) {
            $lab = ComputerLab::findOrFail($this->labId);
            $lab->update($data);
        } else {
            ComputerLab::create($data);
        }

        return redirect()->route('admin.computer-labs.index');
    }

    public function render()
    {
        return view('livewire.admin.computer-labs.form');
    }
}
