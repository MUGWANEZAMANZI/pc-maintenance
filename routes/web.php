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
use App\Livewire\User\Dashboard as UserDashboard;
use App\Livewire\User\Requests\Index as UserRequestsIndex;
use App\Livewire\User\Requests\Form as UserRequestForm;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Admin\Users\Form as AdminUserForm;
use App\Livewire\Admin\ComputerLabs\Index as AdminComputerLabsIndex;
use App\Livewire\Admin\ComputerLabs\Form as AdminComputerLabForm;
use App\Livewire\Admin\Equipment\Index as AdminEquipmentIndex;
use App\Livewire\Admin\Equipment\Form as AdminEquipmentForm;
use App\Livewire\Admin\Buildings\Index as AdminBuildingsIndex;
use App\Livewire\Admin\Buildings\Form as AdminBuildingForm;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === User::ROLE_ADMIN) {
        return redirect('/admin/dashboard');
    } elseif ($user->role === User::ROLE_TECHNICIAN) {
        return redirect('/technician/dashboard');
    } elseif ($user->role === User::ROLE_USER) {
        return redirect('/user/dashboard');
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
    Route::get('/computer-labs', AdminComputerLabsIndex::class)->name('admin.computer-labs.index');
    Route::get('/computer-labs/create', AdminComputerLabForm::class)->name('admin.computer-labs.create');
    Route::get('/computer-labs/{id}/edit', AdminComputerLabForm::class)->name('admin.computer-labs.edit');
    Route::get('/technicians', TechniciansIndex::class)->name('admin.technicians.index');
    Route::get('/technicians/create', TechnicianForm::class)->name('admin.technicians.create');
    Route::get('/technicians/{id}/edit', TechnicianForm::class)->name('admin.technicians.edit');
    Route::get('/users', AdminUsersIndex::class)->name('admin.users.index');
    Route::get('/users/create', AdminUserForm::class)->name('admin.users.create');
    Route::get('/users/{id}/edit', AdminUserForm::class)->name('admin.users.edit');
    Route::get('/buildings', AdminBuildingsIndex::class)->name('admin.buildings.index');
    Route::get('/buildings/create', AdminBuildingForm::class)->name('admin.buildings.create');
    Route::get('/buildings/{id}/edit', AdminBuildingForm::class)->name('admin.buildings.edit');
    Route::get('/equipment', AdminEquipmentIndex::class)->name('admin.equipment.index');
    Route::get('/equipment/create/{type}', AdminEquipmentForm::class)->name('admin.equipment.create');
    Route::get('/equipment/edit/{type}/{id}', AdminEquipmentForm::class)->name('admin.equipment.edit');
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

// User routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('user.dashboard');
    Route::get('/requests', UserRequestsIndex::class)->name('user.requests.index');
    Route::get('/requests/create', UserRequestForm::class)->name('user.requests.create');
});
