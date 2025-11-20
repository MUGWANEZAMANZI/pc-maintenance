<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Technicians') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="/admin/technicians/create" wire:navigate class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Add Technician</a>
                    
    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr class="text-left text-sm text-gray-600">
                <th class="p-2">Name</th>
                <th class="p-2">Email</th>
                <th class="p-2">Status</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse($technicians as $t)
                <tr class="border-t">
                    <td class="p-2">{{ $t->name }}</td>
                    <td class="p-2">{{ $t->email }}</td>
                    <td class="p-2">{{ $t->availability_status ?? '-' }}</td>
                    <td class="p-2">
                        <a href="/admin/technicians/{{ $t->id }}/edit" class="text-blue-600 hover:underline">Edit</a>
                        <button wire:click="delete({{ $t->id }})" class="text-red-600 hover:underline ml-2">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-4 text-center text-gray-500">No technicians found.</td></tr>
            @endforelse
        </tbody>
    </table>
                </div>
            </div>
        </div>
    </div>
</div>
