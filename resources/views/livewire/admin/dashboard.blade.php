<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 shadow rounded"><div class="text-sm text-gray-500">PCs</div><div class="text-xl font-bold">{{ $pcCount }}</div></div>
                    <div class="bg-gray-50 p-4 shadow rounded"><div class="text-sm text-gray-500">Accessories</div><div class="text-xl font-bold">{{ $accessoryCount }}</div></div>
                    <div class="bg-gray-50 p-4 shadow rounded"><div class="text-sm text-gray-500">Network Devices</div><div class="text-xl font-bold">{{ $networkDeviceCount }}</div></div>
                </div>

                <h2 class="text-lg font-semibold mb-2">Available (Unassigned)</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 p-4 shadow rounded"><div class="text-sm text-gray-600">Available PCs</div><div class="text-xl font-bold">{{ $availablePcCount }}</div></div>
                    <div class="bg-green-50 p-4 shadow rounded"><div class="text-sm text-gray-600">Available Accessories</div><div class="text-xl font-bold">{{ $availableAccessoryCount }}</div></div>
                    <div class="bg-green-50 p-4 shadow rounded"><div class="text-sm text-gray-600">Available Network Devices</div><div class="text-xl font-bold">{{ $availableNetworkDeviceCount }}</div></div>
                </div>
                <h2 class="text-xl font-semibold mb-4 mt-6">Equipment Health Overview</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($statusSummary as $status => $count)
                        <div class="bg-gray-50 p-4 shadow rounded">
                            <div class="text-sm text-gray-500">{{ $status }}</div>
                            <div class="text-lg font-bold">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
                
                @if(isset($categoryStatus))
                <h2 class="text-xl font-semibold mb-4 mt-6">Health by Category</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 shadow rounded">
                        <h3 class="font-semibold mb-2">PCs</h3>
                        <div class="text-sm">Healthy: {{ $categoryStatus['pc']['Healthy'] ?? 0 }}</div>
                        <div class="text-sm">Malfunctioning: {{ $categoryStatus['pc']['Malfunctioning'] ?? 0 }}</div>
                        <div class="text-sm">Dead: {{ $categoryStatus['pc']['Dead'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 shadow rounded">
                        <h3 class="font-semibold mb-2">Accessories</h3>
                        <div class="text-sm">Healthy: {{ $categoryStatus['accessory']['Healthy'] ?? 0 }}</div>
                        <div class="text-sm">Malfunctioning: {{ $categoryStatus['accessory']['Malfunctioning'] ?? 0 }}</div>
                        <div class="text-sm">Dead: {{ $categoryStatus['accessory']['Dead'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 shadow rounded">
                        <h3 class="font-semibold mb-2">Network Devices</h3>
                        <div class="text-sm">Healthy: {{ $categoryStatus['network']['Healthy'] ?? 0 }}</div>
                        <div class="text-sm">Malfunctioning: {{ $categoryStatus['network']['Malfunctioning'] ?? 0 }}</div>
                        <div class="text-sm">Dead: {{ $categoryStatus['network']['Dead'] ?? 0 }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
