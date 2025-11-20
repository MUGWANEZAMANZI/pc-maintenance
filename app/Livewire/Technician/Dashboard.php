<?php

namespace App\Livewire\Technician;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;
use App\Models\Request;
use App\Models\Report;

class Dashboard extends Component
{
    public int $myPcCount = 0;
    public int $myAccessoryCount = 0;
    public int $myNetworkDeviceCount = 0;
    public array $myRequests = [];
    public array $statusSummary = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== \App\Models\User::ROLE_TECHNICIAN) {
            abort(403);
        }
        $this->myPcCount = PC::where('technician_id', $user->id)->count();
        $this->myAccessoryCount = Accessory::where('technician_id', $user->id)->count();
        $this->myNetworkDeviceCount = NetworkDevice::where('technician_id', $user->id)->count();
        $this->myRequests = Request::where('technician_id', $user->id)->orderByDesc('date')->take(10)->get()->toArray();
        $this->statusSummary = [
            Report::STATUS_WORKING => Report::where('technician_id', $user->id)->where('status', Report::STATUS_WORKING)->count(),
            Report::STATUS_NOT_WORKING => Report::where('technician_id', $user->id)->where('status', Report::STATUS_NOT_WORKING)->count(),
            Report::STATUS_DAMAGED => Report::where('technician_id', $user->id)->where('status', Report::STATUS_DAMAGED)->count(),
            Report::STATUS_OLD => Report::where('technician_id', $user->id)->where('status', Report::STATUS_OLD)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.technician.dashboard');
    }
}