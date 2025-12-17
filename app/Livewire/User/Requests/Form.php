<?php

namespace App\Livewire\User\Requests;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Request;
use App\Models\User;
use App\Models\ComputerLab;
use App\Models\Building;
use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;

class Form extends Component
{
    public $request_type = '';
    public $description = '';
    public $telephone = '';
    // Department removed
    public $building_id = '';
    public $computer_lab_id = '';
    public $equipment_type = ''; // pc, accessory, network_device, general
    public $pc_id = '';
    public $accessory_id = '';
    public $network_device_id = '';

    public $buildings = [];
    public $computerLabs = [];
    public $pcs = [];
    public $accessories = [];
    public $networkDevices = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403);
        }

        $this->telephone = $user->phone ?? '';
        $this->buildings = Building::orderBy('name')->get();
        $this->computerLabs = [];
    }

    public function updatedComputerLabId($value)
    {
        $this->resetEquipment();
        if ($value) {
            $this->pcs = PC::where('computer_lab_id', $value)->get();
            $this->accessories = Accessory::where('computer_lab_id', $value)->get();
            $this->networkDevices = NetworkDevice::where('computer_lab_id', $value)->get();
        }
    }

    public function updatedEquipmentType()
    {
        $this->pc_id = '';
        $this->accessory_id = '';
        $this->network_device_id = '';
        // Device lists are populated only when a lab is selected
        $this->pcs = [];
        $this->accessories = [];
        $this->networkDevices = [];
        if ($this->computer_lab_id) {
            if ($this->equipment_type === 'pc') {
                $this->pcs = PC::where('computer_lab_id', $this->computer_lab_id)->get();
            } elseif ($this->equipment_type === 'accessory') {
                $this->accessories = Accessory::where('computer_lab_id', $this->computer_lab_id)->get();
            } elseif ($this->equipment_type === 'network_device') {
                $this->networkDevices = NetworkDevice::where('computer_lab_id', $this->computer_lab_id)->get();
            }
        }
    }

    private function resetEquipment()
    {
        $this->pc_id = '';
        $this->accessory_id = '';
        $this->network_device_id = '';
        $this->pcs = [];
        $this->accessories = [];
        $this->networkDevices = [];
    }

    public function save()
    {
        $this->validate([
            'request_type' => 'required|string|max:255',
            'description' => 'required|string',
            'telephone' => 'required|string|max:20',
            'equipment_type' => 'required|in:pc,accessory,network_device,general',
            'building_id' => 'required_unless:equipment_type,general|nullable|exists:buildings,id',
            'computer_lab_id' => 'required_unless:equipment_type,general|nullable|exists:computer_labs,id',
            'pc_id' => 'required_if:equipment_type,pc|nullable|exists:pcs,id',
            'accessory_id' => 'required_if:equipment_type,accessory|nullable|exists:accessories,id',
            'network_device_id' => 'required_if:equipment_type,network_device|nullable|exists:network_devices,id',
        ]);

        $user = Auth::user();

        Request::create([
            'user_id' => $user->id,
            'first_name' => explode(' ', $user->name)[0] ?? $user->name,
            'last_name' => explode(' ', $user->name, 2)[1] ?? '',
            'email' => $user->email,
            'telephone' => $this->telephone,
            'date' => now(),
            'unit' => null,
            'status' => Request::STATUS_PENDING,
            'request_type' => $this->request_type,
            'description' => $this->description,
            'computer_lab_id' => $this->computer_lab_id,
            'pc_id' => $this->equipment_type === 'pc' ? $this->pc_id : null,
            'accessory_id' => $this->equipment_type === 'accessory' ? $this->accessory_id : null,
            'network_device_id' => $this->equipment_type === 'network_device' ? $this->network_device_id : null,
        ]);

        session()->flash('message', 'Request submitted successfully!');
        return redirect()->route('user.requests.index');
    }

    public function render()
    {
        return view('livewire.user.requests.form');
    }

    public function updated($property, $value)
    {
        if ($property === 'building_id') {
            $this->updatedBuildingId($value);
        } elseif ($property === 'computer_lab_id') {
            $this->updatedComputerLabId($value);
        }
    }

    public function updatedBuildingId($value)
    {
        $this->computer_lab_id = '';
        $this->resetEquipment();
        if ($value) {
            $this->computerLabs = ComputerLab::where('building_id', $value)->orderBy('name')->get();
        } else {
            $this->computerLabs = [];
        }
    }
}
