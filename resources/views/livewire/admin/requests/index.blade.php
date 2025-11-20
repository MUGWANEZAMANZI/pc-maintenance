<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    <table class="min-w-full bg-white shadow rounded mb-6">
        <thead>
        <tr class="text-left text-sm text-gray-600">
            <th class="p-2">Request</th>
            <th class="p-2">Unit</th>
            <th class="p-2">Type</th>
            <th class="p-2">Status</th>
            <th class="p-2">Technician</th>
            <th class="p-2">Actions</th>
        </tr>
        </thead>
        <tbody class="text-sm">
        @forelse($requests as $r)
            <tr class="border-t">
                <td class="p-2">{{ $r->first_name }} {{ $r->last_name }}</td>
                <td class="p-2">{{ $r->unit }}</td>
                <td class="p-2">{{ $r->request_type }}</td>
                <td class="p-2">{{ $r->status }}</td>
                <td class="p-2">{{ optional($r->technician)->name ?? '-' }}</td>
                <td class="p-2">
                    @if($r->status === \App\Models\Request::STATUS_PENDING)
                        <button wire:click="setAssign({{ $r->id }})" class="text-indigo-600 hover:underline">Assign</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="p-4 text-center text-gray-500">No requests found.</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($assignRequestId)
        <div class="bg-white shadow rounded p-4 max-w-md">
            <h2 class="font-semibold mb-2">Assign Technician</h2>
            <select wire:model="assignTechnicianId" class="w-full border rounded px-3 py-2 mb-3">
                <option value="">-- choose technician --</option>
                @foreach($technicians as $t)
                    <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->availability_status ?? 'n/a' }})</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button wire:click="assign" class="px-4 py-2 bg-indigo-600 text-white rounded" @disabled(!$assignTechnicianId)>Assign</button>
                <button wire:click="$set('assignRequestId',0)" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
            </div>
        </div>
    @endif
                </div>
            </div>
        </div>
    </div>
</div>
