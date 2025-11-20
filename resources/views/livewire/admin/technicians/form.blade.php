<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $technician ? __('Edit Technician') : __('Add Technician') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    <form wire:submit.prevent="save" class="space-y-4 max-w-lg">
        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" wire:model="name" class="w-full border rounded px-3 py-2" />
            @error('name') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full border rounded px-3 py-2" />
            @error('email') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password {{ $technician ? '(leave blank to keep)' : '' }}</label>
            <input type="password" wire:model="password" class="w-full border rounded px-3 py-2" />
            @error('password') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Availability Status</label>
            <select wire:model="availability_status" class="w-full border rounded px-3 py-2">
                <option value="">-- select --</option>
                <option value="Available">Available</option>
                <option value="Not available">Not available</option>
                <option value="Busy">Busy</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Save</button>
                        <a href="/admin/technicians" wire:navigate class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
