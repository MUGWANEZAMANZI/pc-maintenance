<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Technicians\Index as TechniciansIndex;
use App\Livewire\Admin\Technicians\Form as TechnicianForm;
use App\Livewire\Admin\Requests\Index as RequestsIndex;
use App\Livewire\Technician\Dashboard as TechnicianDashboard;
use App\Livewire\Technician\Equipment\Index as TechnicianEquipmentIndex;
use App\Livewire\Technician\Equipment\PCForm as TechnicianPCForm;
use App\Livewire\Technician\Equipment\AccessoryForm as TechnicianAccessoryForm;
use App\Livewire\Technician\Equipment\NetworkDeviceForm as TechnicianNetworkDeviceForm;
use App\Livewire\Technician\Requests\Index as TechnicianRequestsIndex;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === User::ROLE_ADMIN) {
        return redirect('/admin/dashboard');
    } elseif ($user->role === User::ROLE_TECHNICIAN) {
        return redirect('/technician/dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/technicians', TechniciansIndex::class)->name('admin.technicians.index');
    Route::get('/technicians/create', TechnicianForm::class)->name('admin.technicians.create');
    Route::get('/technicians/{id}/edit', TechnicianForm::class)->name('admin.technicians.edit');
    Route::get('/requests', RequestsIndex::class)->name('admin.requests.index');
});

// Technician routes
Route::middleware(['auth', 'verified', 'role:technician'])->prefix('technician')->group(function () {
    Route::get('/dashboard', TechnicianDashboard::class)->name('technician.dashboard');
    Route::get('/equipment', TechnicianEquipmentIndex::class)->name('technician.equipment.index');
    Route::get('/equipment/pc/create', TechnicianPCForm::class)->name('technician.equipment.pc.create');
    Route::get('/equipment/pc/{id}/edit', TechnicianPCForm::class)->name('technician.equipment.pc.edit');
    Route::get('/equipment/accessory/create', TechnicianAccessoryForm::class)->name('technician.equipment.accessory.create');
    Route::get('/equipment/accessory/{id}/edit', TechnicianAccessoryForm::class)->name('technician.equipment.accessory.edit');
    Route::get('/equipment/network-device/create', TechnicianNetworkDeviceForm::class)->name('technician.equipment.network-device.create');
    Route::get('/equipment/network-device/{id}/edit', TechnicianNetworkDeviceForm::class)->name('technician.equipment.network-device.edit');
    Route::get('/requests', TechnicianRequestsIndex::class)->name('technician.requests.index');
});
