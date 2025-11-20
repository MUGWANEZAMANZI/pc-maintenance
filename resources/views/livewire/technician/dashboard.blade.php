<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Technician Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 shadow rounded"><div class="text-sm text-gray-500">My PCs</div><div class="text-xl font-bold">{{ $myPcCount }}</div></div>
                    <div class="bg-white p-4 shadow rounded"><div class="text-sm text-gray-500">My Accessories</div><div class="text-xl font-bold">{{ $myAccessoryCount }}</div></div>
                    <div class="bg-white p-4 shadow rounded"><div class="text-sm text-gray-500">My Network Devices</div><div class="text-xl font-bold">{{ $myNetworkDeviceCount }}</div></div>
    </div>
    <h2 class="text-xl font-semibold mb-2">Recent Requests</h2>
    <table class="min-w-full bg-white shadow rounded mb-6">
        <thead>
            <tr class="text-left text-sm text-gray-600">
                <th class="p-2">Request</th>
                <th class="p-2">Type</th>
                <th class="p-2">Status</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse($myRequests as $r)
                <tr class="border-t">
                    <td class="p-2">{{ $r['first_name'] }} {{ $r['last_name'] }}</td>
                    <td class="p-2">{{ $r['request_type'] }}</td>
                    <td class="p-2">{{ $r['status'] }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="p-4 text-center text-gray-500">No assigned requests.</td></tr>
            @endforelse
        </tbody>
    </table>
    <h2 class="text-xl font-semibold mb-2">My Reports Summary</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($statusSummary as $status => $count)
            <div class="bg-white p-4 shadow rounded">
                <div class="text-sm text-gray-500">{{ $status }}</div>
                <div class="text-lg font-bold">{{ $count }}</div>
            </div>
        @endforeach
    </div>
                </div>
            </div>
        </div>
    </div>
</div>