<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Request;
use App\Models\User;

class Dashboard extends Component
{
    public $stats = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== User::ROLE_USER) {
            abort(403);
        }

        $this->loadStats();
    }

    public function loadStats(): void
    {
        $userId = Auth::id();
        
        $this->stats = [
            'total' => Request::where('user_id', $userId)->count(),
            'pending' => Request::where('user_id', $userId)->where('status', Request::STATUS_PENDING)->count(),
            'assigned' => Request::where('user_id', $userId)->where('status', Request::STATUS_ASSIGNED)->count(),
            'fixed' => Request::where('user_id', $userId)->where('status', Request::STATUS_FIXED)->count(),
            'not_fixed' => Request::where('user_id', $userId)->where('status', Request::STATUS_NOT_FIXED)->count(),
        ];
    }

    public function render()
    {
        $recentRequests = Request::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('livewire.user.dashboard', [
            'recentRequests' => $recentRequests
        ]);
    }
}
