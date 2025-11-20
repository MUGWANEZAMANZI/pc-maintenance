<?php

namespace App\Livewire\Technician\Equipment;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PC;

class PCForm extends Component
{
    public ?PC $pc = null;
    public string $specifications = '';
    public string $hdd = '';
    public string $ram = '';
    public string $os = '';
    public string $brand = '';
    public string $registration_year = '';

    public function mount(?int $id = null): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== \App\Models\User::ROLE_TECHNICIAN) {
            abort(403);
        }
        if ($id) {
            $this->pc = PC::where('technician_id', $user->id)->findOrFail($id);
            $this->fill($this->pc->only(['specifications','hdd','ram','os','brand','registration_year']));
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'specifications' => 'nullable|string',
            'hdd' => 'required|string',
            'ram' => 'required|string',
            'os' => 'required|string',
            'brand' => 'required|string',
            'registration_year' => 'required|digits:4'
        ]);
        $data['technician_id'] = Auth::id();
        if ($this->pc) {
            $this->pc->update($data);
        } else {
            PC::create($data);
        }
        redirect('/technician/equipment');
    }

    public function render()
    {
        return view('livewire.technician.equipment.pc-form');
    }
}