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
        $this->requests = Request::select([
                'id','first_name','last_name','email','telephone','date','unit','status','request_type',
                'technician_id','user_id','description','pc_id','accessory_id','network_device_id'
            ])
            ->with([
                'pc:id,device_name,brand,os',
                'accessory:id,device_name,type,brand',
                'networkDevice:id,device_name,type,brand',
                'technician:id,name',
                'user:id'
            ])
            ->orderByDesc('date')
            ->get();

        $this->technicians = User::where('role', User::ROLE_TECHNICIAN)
            ->select('id','name','availability_status')
            ->get();
    }

    public function loadEquipmentHealth(): void
    {
        $equipment = collect();

        $pcs = PC::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id')
            ->with([
                'building:id,name',
                'computerLab:id,name',
                'technician:id,name'
            ])
            ->get()
            ->map(function($pc) {
                $pc->equipment_type = 'PC';
                return $pc;
            });
        $equipment = $equipment->merge($pcs);

        $accessories = Accessory::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id','type')
            ->with([
                'building:id,name',
                'computerLab:id,name',
                'technician:id,name'
            ])
            ->get()
            ->map(function($acc) {
                $acc->equipment_type = 'Accessory';
                return $acc;
            });
        $equipment = $equipment->merge($accessories);

        $devices = NetworkDevice::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id','type')
            ->with([
                'building:id,name',
                'computerLab:id,name',
                'technician:id,name'
            ])
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
            'pc' => PC::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id')
                ->with(['building:id,name','computerLab:id,name','technician:id,name'])
                ->findOrFail($id),
            'accessory' => Accessory::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id','type')
                ->with(['building:id,name','computerLab:id,name','technician:id,name'])
                ->findOrFail($id),
            'network_device' => NetworkDevice::select('id','device_name','brand','health','building_id','computer_lab_id','technician_id','type')
                ->with(['building:id,name','computerLab:id,name','technician:id,name'])
                ->findOrFail($id),
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
        if (!$this->assignTechnicianId) {
            $this->addError('assignTechnicianId', 'Please choose a technician.');
            return;
        }

        $tech = User::where('role', User::ROLE_TECHNICIAN)->find($this->assignTechnicianId);
        if (!$tech) {
            $this->addError('assignTechnicianId', 'Technician not found.');
            return;
        }

        $status = strtolower(trim($tech->availability_status ?? ''));
        if ($status !== 'available') {
            $this->addError('assignTechnicianId', 'This technician is not available.');
            return;
        }

        $req = Request::findOrFail($this->assignRequestId);
        $req->technician_id = $this->assignTechnicianId;
        $req->status = Request::STATUS_ASSIGNED;
        $req->save();
        $this->assignRequestId = 0;
        $this->assignTechnicianId = 0;
        $this->loadData();
    }

    public function assignTo(int $technicianId): void
    {
        $this->assignTechnicianId = $technicianId;
        $this->assign();
    }

    public function render()
    {
        return view('livewire.admin.requests.index');
    }
}
