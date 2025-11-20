<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Requests') }}
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
                <td class="p-2">
                    @if(in_array($r->status, [\App\Models\Request::STATUS_ASSIGNED, \App\Models\Request::STATUS_NOT_FIXED]))
                        <button wire:click="markFixed({{ $r->id }})" class="text-green-600 hover:underline">Mark Fixed</button>
                    @endif
                    @if($r->status === \App\Models\Request::STATUS_ASSIGNED)
                        <button wire:click="markNotFixed({{ $r->id }})" class="text-red-600 hover:underline ml-2">Not Fixed</button>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="p-4 text-center text-gray-500">No requests assigned.</td></tr>
        @endforelse
        </tbody>
    </table>
                </div>
            </div>
        </div>
    </div>
</div>