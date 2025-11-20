<?php

namespace App\Livewire\Technician\Equipment;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\NetworkDevice;

class NetworkDeviceForm extends Component
{
    public ?NetworkDevice $device = null;
    public string $type = '';
    public string $brand = '';
    public string $registration_year = '';

    public function mount(?int $id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== \App\Models\User::ROLE_TECHNICIAN) { abort(403); }
        if ($id) {
            $this->device = NetworkDevice::where('technician_id', $user->id)->findOrFail($id);
            $this->fill($this->device->only(['type','brand','registration_year']));
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
        if ($this->device) {
            $this->device->update($data);
        } else {
            NetworkDevice::create($data);
        }
        redirect('/technician/equipment');
    }

    public function render()
    {
        return view('livewire.technician.equipment.network-device-form');
    }
}