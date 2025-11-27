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

class Form extends Component
{
    public $equipmentId;
    public $equipment_type = 'pc'; // pc, accessory, network_device
    
    // Common fields
    public $device_name = '';
    public $brand = '';
    public $registration_year = '';
    public $health = 'healthy';
    public $building_id = '';
    public $department_id = '';
    public $computer_lab_id = '';
    
    // PC specific
    public $specifications = '';
    public $hdd = '';
    public $ram = '';
    public $os = '';
    
    // Accessory/Network Device specific
    public $type = '';
    
    // Data
    public $buildings = [];
    public $departments = [];
    public $computerLabs = [];

    public function mount($type = 'pc', $id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        $this->equipment_type = $type;
        $this->buildings = Building::orderBy('name')->get();
        $this->departments = Department::orderBy('name')->get();

        if ($id) {
            $this->equipmentId = $id;
            $this->loadEquipment($type, $id);
        }
    }

    private function loadEquipment($type, $id)
    {
        $equipment = match($type) {
            'pc' => PC::findOrFail($id),
            'accessory' => Accessory::findOrFail($id),
            'network_device' => NetworkDevice::findOrFail($id),
        };

        $this->device_name = $equipment->device_name ?? '';
        $this->brand = $equipment->brand;
        $this->registration_year = $equipment->registration_year;
        $this->health = $equipment->health;
        $this->building_id = $equipment->building_id ?? '';
        $this->computer_lab_id = $equipment->computer_lab_id ?? '';
        
        if ($equipment->computerLab) {
            $this->department_id = $equipment->computerLab->department_id;
            $this->updatedDepartmentId($this->department_id);
        }

        if ($type === 'pc') {
            $this->specifications = $equipment->specifications;
            $this->hdd = $equipment->hdd;
            $this->ram = $equipment->ram;
            $this->os = $equipment->os;
        } else {
            $this->type = $equipment->type;
        }
    }

    public function updatedDepartmentId($value)
    {
        $this->computer_lab_id = '';
        if ($value) {
            $this->computerLabs = ComputerLab::where('department_id', $value)->orderBy('name')->get();
        } else {
            $this->computerLabs = [];
        }
    }

    public function save()
    {
        $commonRules = [
            'device_name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'registration_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'health' => 'required|in:healthy,malfunctioning,dead',
            'building_id' => 'nullable|exists:buildings,id',
            'computer_lab_id' => 'nullable|exists:computer_labs,id',
        ];

        if ($this->equipment_type === 'pc') {
            $rules = array_merge($commonRules, [
                'specifications' => 'required|string',
                'hdd' => 'required|string',
                'ram' => 'required|string',
                'os' => 'required|string',
            ]);
        } else {
            $rules = array_merge($commonRules, [
                'type' => 'required|string|max:255',
            ]);
        }

        $this->validate($rules);

        $commonData = [
            'device_name' => $this->device_name,
            'brand' => $this->brand,
            'registration_year' => $this->registration_year,
            'health' => $this->health,
            'building_id' => $this->building_id ?: null,
            'computer_lab_id' => $this->computer_lab_id ?: null,
            'technician_id' => null, // Admin adds, not assigned to technician yet
        ];

        if ($this->equipment_type === 'pc') {
            $data = array_merge($commonData, [
                'specifications' => $this->specifications,
                'hdd' => $this->hdd,
                'ram' => $this->ram,
                'os' => $this->os,
            ]);

            if ($this->equipmentId) {
                PC::findOrFail($this->equipmentId)->update($data);
            } else {
                PC::create($data);
            }
        } elseif ($this->equipment_type === 'accessory') {
            $data = array_merge($commonData, ['type' => $this->type]);
            
            if ($this->equipmentId) {
                Accessory::findOrFail($this->equipmentId)->update($data);
            } else {
                Accessory::create($data);
            }
        } else {
            $data = array_merge($commonData, ['type' => $this->type]);
            
            if ($this->equipmentId) {
                NetworkDevice::findOrFail($this->equipmentId)->update($data);
            } else {
                NetworkDevice::create($data);
            }
        }

        return redirect()->route('admin.equipment.index');
    }

    public function render()
    {
        return view('livewire.admin.equipment.form');
    }
}
