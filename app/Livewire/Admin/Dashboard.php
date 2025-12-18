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

    public int $availablePcCount = 0;
    public int $availableAccessoryCount = 0;
    public int $availableNetworkDeviceCount = 0;

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

        // Available = not assigned to any technician
        $this->availablePcCount = PC::whereNull('technician_id')->count();
        $this->availableAccessoryCount = Accessory::whereNull('technician_id')->count();
        $this->availableNetworkDeviceCount = NetworkDevice::whereNull('technician_id')->count();

        // Health-based summary across all equipment
        $healthy = PC::where('health', 'healthy')->count()
            + Accessory::where('health', 'healthy')->count()
            + NetworkDevice::where('health', 'healthy')->count();
        $malfunctioning = PC::where('health', 'malfunctioning')->count()
            + Accessory::where('health', 'malfunctioning')->count()
            + NetworkDevice::where('health', 'malfunctioning')->count();
        $dead = PC::where('health', 'dead')->count()
            + Accessory::where('health', 'dead')->count()
            + NetworkDevice::where('health', 'dead')->count();

        $this->statusSummary = [
            'Healthy' => $healthy,
            'Malfunctioning' => $malfunctioning,
            'Dead' => $dead,
        ];

        $this->categoryStatus = [
            'pc' => [
                'Healthy' => PC::where('health', 'healthy')->count(),
                'Malfunctioning' => PC::where('health', 'malfunctioning')->count(),
                'Dead' => PC::where('health', 'dead')->count(),
            ],
            'accessory' => [
                'Healthy' => Accessory::where('health', 'healthy')->count(),
                'Malfunctioning' => Accessory::where('health', 'malfunctioning')->count(),
                'Dead' => Accessory::where('health', 'dead')->count(),
            ],
            'network' => [
                'Healthy' => NetworkDevice::where('health', 'healthy')->count(),
                'Malfunctioning' => NetworkDevice::where('health', 'malfunctioning')->count(),
                'Dead' => NetworkDevice::where('health', 'dead')->count(),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
