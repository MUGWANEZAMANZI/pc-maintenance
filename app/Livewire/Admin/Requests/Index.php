<?php

namespace App\Livewire\Admin\Requests;

use App\Models\Request;
use App\Models\User;
use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $requests = [];
    public $technicians = [];
    public int $assignRequestId = 0;
    public int $assignTechnicianId = 0;
    public $showHealthMap = false;
    public $equipment = [];
    public $selectedEquipment = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadData();
        $this->loadEquipmentHealth();
    }

    public function loadData(): void
    {
        $this->requests = Request::orderByDesc('date')->get();
        $this->technicians = User::where('role', User::ROLE_TECHNICIAN)->get();
    }

    public function loadEquipmentHealth(): void
    {
        $equipment = collect();

        $pcs = PC::with(['building', 'computerLab', 'technician'])
            ->get()
            ->map(function($pc) {
                $pc->equipment_type = 'PC';
                return $pc;
            });
        $equipment = $equipment->merge($pcs);

        $accessories = Accessory::with(['building', 'computerLab', 'technician'])
            ->get()
            ->map(function($acc) {
                $acc->equipment_type = 'Accessory';
                return $acc;
            });
        $equipment = $equipment->merge($accessories);

        $devices = NetworkDevice::with(['building', 'computerLab', 'technician'])
            ->get()
            ->map(function($dev) {
                $dev->equipment_type = 'Network Device';
                return $dev;
            });
        $equipment = $equipment->merge($devices);

        $this->equipment = $equipment;
    }

    public function toggleHealthMap(): void
    {
        $this->showHealthMap = !$this->showHealthMap;
        if ($this->showHealthMap) {
            $this->loadEquipmentHealth();
        }
    }

    public function selectEquipment($type, $id): void
    {
        $equipment = match($type) {
            'pc' => PC::with(['building', 'computerLab', 'technician'])->findOrFail($id),
            'accessory' => Accessory::with(['building', 'computerLab', 'technician'])->findOrFail($id),
            'network_device' => NetworkDevice::with(['building', 'computerLab', 'technician'])->findOrFail($id),
        };
        $equipment->equipment_type = match($type) {
            'pc' => 'PC',
            'accessory' => 'Accessory',
            'network_device' => 'Network Device',
        };
        $this->selectedEquipment = $equipment;
    }

    public function closeEquipmentDetails(): void
    {
        $this->selectedEquipment = null;
    }

    public function setAssign(int $requestId): void
    {
        $this->assignRequestId = $requestId;
    }

    public function assign(): void
    {
        $req = Request::findOrFail($this->assignRequestId);
        $req->technician_id = $this->assignTechnicianId;
        $req->status = Request::STATUS_ASSIGNED;
        $req->save();
        $this->assignRequestId = 0;
        $this->assignTechnicianId = 0;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.requests.index');
    }
}
