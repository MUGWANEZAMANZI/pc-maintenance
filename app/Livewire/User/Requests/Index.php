<?php

namespace App\Livewire\User\Requests;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Request;
use App\Models\User;

class Index extends Component
{
    public $requests = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403);
        }
        $this->loadRequests();
    }

    public function loadRequests(): void
    {
        $this->requests = Request::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.requests.index');
    }
}
