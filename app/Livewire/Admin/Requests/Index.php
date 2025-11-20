<?php

namespace App\Livewire\Admin\Requests;

use App\Models\Request;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $requests = [];
    public $technicians = [];
    public int $assignRequestId = 0;
    public int $assignTechnicianId = 0;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_ADMIN) {
            abort(403);
        }
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->requests = Request::orderByDesc('date')->get();
        $this->technicians = User::where('role', User::ROLE_TECHNICIAN)->get();
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
