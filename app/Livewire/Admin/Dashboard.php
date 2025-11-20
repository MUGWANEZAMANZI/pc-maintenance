<?php

namespace App\Livewire\Admin;

use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;
use App\Models\Report;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public int $pcCount = 0;
    public int $accessoryCount = 0;
    public int $networkDeviceCount = 0;

    public array $statusSummary = [];
    public array $categoryStatus = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $this->pcCount = PC::count();
        $this->accessoryCount = Accessory::count();
        $this->networkDeviceCount = NetworkDevice::count();

        // Simple status summary from reports (latest per asset could be refined later)
        $this->statusSummary = [
            Report::STATUS_WORKING => Report::where('status', Report::STATUS_WORKING)->count(),
            Report::STATUS_NOT_WORKING => Report::where('status', Report::STATUS_NOT_WORKING)->count(),
            Report::STATUS_DAMAGED => Report::where('status', Report::STATUS_DAMAGED)->count(),
            Report::STATUS_OLD => Report::where('status', Report::STATUS_OLD)->count(),
        ];

        $this->categoryStatus = [
            'pc' => [
                'Working' => PC::whereHas('reports', function($q){ $q->where('status', Report::STATUS_WORKING); })->count(),
                'Not working' => PC::whereHas('reports', function($q){ $q->where('status', Report::STATUS_NOT_WORKING); })->count(),
            ],
            'accessory' => [
                'Working' => Accessory::whereHas('reports', function($q){ $q->where('status', Report::STATUS_WORKING); })->count(),
                'Not working' => Accessory::whereHas('reports', function($q){ $q->where('status', Report::STATUS_NOT_WORKING); })->count(),
            ],
            'network' => [
                'Working' => NetworkDevice::whereHas('reports', function($q){ $q->where('status', Report::STATUS_WORKING); })->count(),
                'Not working' => NetworkDevice::whereHas('reports', function($q){ $q->where('status', Report::STATUS_NOT_WORKING); })->count(),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
