<?php

namespace App\Livewire\Admin\Buildings;

use App\Models\Building;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Form extends Component
{
    public $buildingId;
    public $name = '';
    public $code = '';
    public $location = '';
    public $description = '';

    public function mount($id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        if ($id) {
            $this->buildingId = $id;
            $building = Building::findOrFail($id);
            $this->name = $building->name;
            $this->code = $building->code ?? '';
            $this->location = $building->location ?? '';
            $this->description = $building->description ?? '';
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'location' => $this->location,
            'description' => $this->description,
        ];

        if ($this->buildingId) {
            Building::findOrFail($this->buildingId)->update($data);
        } else {
            Building::create($data);
        }

        return redirect()->route('admin.buildings.index');
    }

    public function render()
    {
        return view('livewire.admin.buildings.form');
    }
}
