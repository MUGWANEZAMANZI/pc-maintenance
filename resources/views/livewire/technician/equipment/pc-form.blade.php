<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $pc ? __('Edit PC') : __('Add PC') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    <form wire:submit.prevent="save" class="space-y-4 max-w-xl">
        <div>
            <label class="block text-sm font-medium mb-1">Specifications (optional)</label>
            <textarea wire:model="specifications" class="w-full border rounded px-3 py-2" rows="3"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">HDD</label>
                <input type="text" wire:model="hdd" class="w-full border rounded px-3 py-2" />
                @error('hdd') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">RAM</label>
                <input type="text" wire:model="ram" class="w-full border rounded px-3 py-2" />
                @error('ram') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">OS</label>
                <input type="text" wire:model="os" class="w-full border rounded px-3 py-2" />
                @error('os') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
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