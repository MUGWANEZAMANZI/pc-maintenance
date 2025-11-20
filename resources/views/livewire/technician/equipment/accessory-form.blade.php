<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $accessory ? __('Edit Accessory') : __('Add Accessory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    <form wire:submit.prevent="save" class="space-y-4 max-w-xl">
        <div>
            <label class="block text-sm font-medium mb-1">Type</label>
            <input type="text" wire:model="type" class="w-full border rounded px-3 py-2" />
            @error('type') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Brand</label>
            <input type="text" wire:model="brand" class="w-full border rounded px-3 py-2" />
            @error('brand') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Registration Year</label>
            <input type="text" wire:model="registration_year" class="w-full border rounded px-3 py-2" />
            @error('registration_year') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Save</button>
                        <a href="/technician/equipment" wire:navigate class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>