<?php

namespace App\Livewire\Admin\Equipment;

use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;
use App\Models\Building;
use App\Models\Department;
use App\Models\ComputerLab;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $equipment = [];
    public $filter = 'all'; // all, pcs, accessories, network_devices
    public $healthFilter = 'all'; // all, healthy, malfunctioning, dead
    public $showDeleteModal = false;
    public $deleteType = '';
    public $deleteId = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadEquipment();
    }

    public function loadEquipment(): void
    {
        $equipment = collect();

        if ($this->filter === 'all' || $this->filter === 'pcs') {
            $pcs = PC::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id')
                ->with(['building:id,name','computerLab:id,name','technician:id,name'])
                ->when($this->healthFilter !== 'all', function($query) {
                    $query->where('health', $this->healthFilter);
                })
                ->get()
                ->map(function($pc) {
                    $pc->equipment_type = 'PC';
                    return $pc;
                });
            $equipment = $equipment->merge($pcs);
        }

        if ($this->filter === 'all' || $this->filter === 'accessories') {
            $accessories = Accessory::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id','type')
                ->with(['building:id,name','computerLab:id,name','technician:id,name'])
                ->when($this->healthFilter !== 'all', function($query) {
                    $query->where('health', $this->healthFilter);
                })
                ->get()
                ->map(function($acc) {
                    $acc->equipment_type = 'Accessory';
                    return $acc;
                });
            $equipment = $equipment->merge($accessories);
        }

        if ($this->filter === 'all' || $this->filter === 'network_devices') {
            $devices = NetworkDevice::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id','type')
                ->with(['building:id,name','computerLab:id,name','technician:id,name'])
                ->when($this->healthFilter !== 'all', function($query) {
                    $query->where('health', $this->healthFilter);
                })
                ->get()
                ->map(function($dev) {
                    $dev->equipment_type = 'Network Device';
                    return $dev;
                });
            $equipment = $equipment->merge($devices);
        }

        $this->equipment = $equipment;
    }

    public function updatedFilter()
    {
        $this->loadEquipment();
    }

    public function updatedHealthFilter()
    {
        $this->loadEquipment();
    }

    public function confirmDelete($type, $id): void
    {
        $this->deleteType = $type;
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            match($this->deleteType) {
                'pc' => PC::findOrFail($this->deleteId)->delete(),
                'accessory' => Accessory::findOrFail($this->deleteId)->delete(),
                'network_device' => NetworkDevice::findOrFail($this->deleteId)->delete(),
            };
            $this->deleteId = 0;
            $this->deleteType = '';
            $this->showDeleteModal = false;
            $this->loadEquipment();
        }
    }

    public function render()
    {
        return view('livewire.admin.equipment.index');
    }
}
