<?php

namespace App\Livewire\Technician\Equipment;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Accessory;

class AccessoryForm extends Component
{
    public ?Accessory $accessory = null;
    public string $type = '';
    public string $brand = '';
    public string $registration_year = '';

    public function mount(?int $id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== \App\Models\User::ROLE_TECHNICIAN) { abort(403); }
        if ($id) {
            $this->accessory = Accessory::where('technician_id', $user->id)->findOrFail($id);
            $this->fill($this->accessory->only(['type','brand','registration_year']));
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'type' => 'required|string',
            'brand' => 'required|string',
            'registration_year' => 'required|digits:4'
        ]);
        $data['technician_id'] = Auth::id();
        if ($this->accessory) {
            $this->accessory->update($data);
        } else {
            Accessory::create($data);
        }
        redirect('/technician/equipment');
    }

    public function render()
    {
        return view('livewire.technician.equipment.accessory-form');
    }
}