<?php

namespace App\Livewire\Technician\Equipment;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;
use App\Models\Report;

class Index extends Component
{
    public $equipment = [];
    public int $updateHealthId = 0;
    public string $updateHealthType = '';
    public string $newHealth = '';
    public int $reportTargetId = 0;
    public string $reportTargetType = '';
    public string $status = '';
    public string $notes = '';

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== \App\Models\User::ROLE_TECHNICIAN) {
            abort(403);
        }
        $this->loadAll();
    }

    public function loadAll(): void
    {
        $uid = Auth::id();
        $equipment = collect();

        $pcs = PC::with(['building', 'computerLab'])
            ->where('technician_id', $uid)
            ->get()
            ->map(function($pc) {
                $pc->equipment_type = 'PC';
                return $pc;
            });
        $equipment = $equipment->merge($pcs);

        $accessories = Accessory::with(['building', 'computerLab'])
            ->where('technician_id', $uid)
            ->get()
            ->map(function($acc) {
                $acc->equipment_type = 'Accessory';
                return $acc;
            });
        $equipment = $equipment->merge($accessories);

        $devices = NetworkDevice::with(['building', 'computerLab'])
            ->where('technician_id', $uid)
            ->get()
            ->map(function($dev) {
                $dev->equipment_type = 'Network Device';
                return $dev;
            });
        $equipment = $equipment->merge($devices);

        $this->equipment = $equipment;
    }

    public function setUpdateHealth(string $type, int $id, string $currentHealth): void
    {
        $this->updateHealthType = $type;
        $this->updateHealthId = $id;
        $this->newHealth = $currentHealth;
    }

    public function updateHealth(): void
    {
        $this->validate([
            'newHealth' => 'required|in:healthy,malfunctioning,dead'
        ]);

        $model = match($this->updateHealthType) {
            'pc' => PC::findOrFail($this->updateHealthId),
            'accessory' => Accessory::findOrFail($this->updateHealthId),
            'network_device' => NetworkDevice::findOrFail($this->updateHealthId),
            default => null,
        };

        if ($model) {
            $model->health = $this->newHealth;
            $model->save();
        }

        $this->updateHealthId = 0;
        $this->updateHealthType = '';
        $this->newHealth = '';
        $this->loadAll();
    }

    public function setReport(string $type, int $id): void
    {
        $this->reportTargetType = $type;
        $this->reportTargetId = $id;
        $this->status = '';
        $this->notes = '';
    }

    public function saveReport(): void
    {
        $data = $this->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);
        $user = Auth::user();
        $model = match($this->reportTargetType) {
            'pc' => PC::findOrFail($this->reportTargetId),
            'accessory' => Accessory::findOrFail($this->reportTargetId),
            'network_device' => NetworkDevice::findOrFail($this->reportTargetId),
            default => null,
        };
        if (!$model) { return; }
        $model->reports()->create([
            'status' => $data['status'],
            'date' => now()->toDateString(),
            'location' => $data['notes'] ? null : null,
            'technician_id' => $user->id,
            'notes' => $data['notes'] ?? null,
        ]);
        $this->reportTargetId = 0;
        $this->reportTargetType = '';
        $this->status = '';
        $this->notes = '';
        $this->loadAll();
    }

    public function render()
    {
        return view('livewire.technician.equipment.index');
    }
}