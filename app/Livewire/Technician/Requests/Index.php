<?php

namespace App\Livewire\Technician\Requests;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Request;

class Index extends Component
{
    public $requests = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== \App\Models\User::ROLE_TECHNICIAN) { abort(403); }
        $this->loadRequests();
    }

    public function loadRequests(): void
    {
        $this->requests = Request::where('technician_id', Auth::id())->orderByDesc('date')->get();
    }

    public function markFixed(int $id): void
    {
        $r = Request::where('technician_id', Auth::id())->findOrFail($id);
        $r->status = Request::STATUS_FIXED;
        $r->save();
        $this->loadRequests();
    }

    public function markNotFixed(int $id): void
    {
        $r = Request::where('technician_id', Auth::id())->findOrFail($id);
        $r->status = Request::STATUS_NOT_FIXED;
        $r->save();
        $this->loadRequests();
    }

    public function render()
    {
        return view('livewire.technician.requests.index');
    }
}