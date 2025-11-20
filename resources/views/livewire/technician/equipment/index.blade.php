<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Equipment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    <div class="mb-4 flex gap-2 flex-wrap">
                    <a href="/technician/equipment/pc/create" wire:navigate class="px-3 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">Add PC</a>
                    <a href="/technician/equipment/accessory/create" wire:navigate class="px-3 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">Add Accessory</a>
                    <a href="/technician/equipment/network-device/create" wire:navigate class="px-3 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">Add Network Device</a>
    </div>
    <h2 class="text-xl font-semibold mt-6 mb-2">PCs</h2>
    <table class="min-w-full bg-white shadow rounded mb-6">
        <thead><tr class="text-left text-sm text-gray-600"><th class="p-2">Brand</th><th class="p-2">Specs</th><th class="p-2">Actions</th></tr></thead>
        <tbody class="text-sm">
        @forelse($pcs as $pc)
            <tr class="border-t">
                <td class="p-2">{{ $pc->brand }}</td>
                <td class="p-2">{{ $pc->specifications ?? ($pc->hdd.' / '.$pc->ram.' / '.$pc->os) }}</td>
                <td class="p-2">
                                <a href="/technician/equipment/pc/{{ $pc->id }}/edit" wire:navigate class="text-blue-600 hover:underline">Edit</a>
                    <button wire:click="setReport('pc', {{ $pc->id }})" class="text-indigo-600 hover:underline ml-2">Report</button>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-4 text-center text-gray-500">No PCs.</td></tr>
        @endforelse
        </tbody>
    </table>
    <h2 class="text-xl font-semibold mt-6 mb-2">Accessories</h2>
    <table class="min-w-full bg-white shadow rounded mb-6">
        <thead><tr class="text-left text-sm text-gray-600"><th class="p-2">Type</th><th class="p-2">Brand</th><th class="p-2">Actions</th></tr></thead>
        <tbody class="text-sm">
        @forelse($accessories as $a)
            <tr class="border-t">
                <td class="p-2">{{ $a->type }}</td>
                <td class="p-2">{{ $a->brand }}</td>
                            <td class="p-2"><a href="/technician/equipment/accessory/{{ $a->id }}/edit" wire:navigate class="text-blue-600 hover:underline">Edit</a><button wire:click="setReport('accessory', {{ $a->id }})" class="text-indigo-600 hover:underline ml-2">Report</button></td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-4 text-center text-gray-500">No accessories.</td></tr>
        @endforelse
        </tbody>
    </table>
    <h2 class="text-xl font-semibold mt-6 mb-2">Network Devices</h2>
    <table class="min-w-full bg-white shadow rounded mb-6">
        <thead><tr class="text-left text-sm text-gray-600"><th class="p-2">Type</th><th class="p-2">Brand</th><th class="p-2">Actions</th></tr></thead>
        <tbody class="text-sm">
        @forelse($networkDevices as $d)
            <tr class="border-t">
                <td class="p-2">{{ $d->type }}</td>
                <td class="p-2">{{ $d->brand }}</td>
                            <td class="p-2"><a href="/technician/equipment/network-device/{{ $d->id }}/edit" wire:navigate class="text-blue-600 hover:underline">Edit</a><button wire:click="setReport('network', {{ $d->id }})" class="text-indigo-600 hover:underline ml-2">Report</button></td>
            </tr>
        @empty
            <tr><td colspan="3" class="p-4 text-center text-gray-500">No network devices.</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($reportTargetId)
        <div class="bg-white shadow rounded p-4 max-w-md">
            <h2 class="font-semibold mb-2">Add Report</h2>
            <form wire:submit.prevent="saveReport" class="space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select wire:model="status" class="w-full border rounded px-3 py-2">
                        <option value="">-- select --</option>
                        <option value="Working">Working</option>
                        <option value="Not working">Not working</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Old">Old</option>
                    </select>
                    @error('status') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Notes</label>
                    <textarea wire:model="notes" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                    <button type="button" wire:click="$set('reportTargetId',0)" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                </div>
            </form>
        </div>
    @endif
                </div>
            </div>
        </div>
    </div>
</div>