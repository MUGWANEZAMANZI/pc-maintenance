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
    public $pcs = [];
    public $accessories = [];
    public $networkDevices = [];
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
        $this->pcs = PC::where('technician_id', $uid)->get();
        $this->accessories = Accessory::where('technician_id', $uid)->get();
        $this->networkDevices = NetworkDevice::where('technician_id', $uid)->get();
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
            'network' => NetworkDevice::findOrFail($this->reportTargetId),
            default => null,
        };
        if (!$model) { return; }
        $model->reports()->create([
            'status' => $data['status'],
            'date' => now()->toDateString(),
            'location' => $data['notes'] ? null : null, // placeholder; location could be added via UI
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