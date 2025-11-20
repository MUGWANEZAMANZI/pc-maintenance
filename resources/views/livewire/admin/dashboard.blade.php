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
                <h2 class="text-xl font-semibold mb-4 mt-6">Equipment Status Overview</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($statusSummary as $status => $count)
                        <div class="bg-gray-50 p-4 shadow rounded">
                            <div class="text-sm text-gray-500">{{ $status }}</div>
                            <div class="text-lg font-bold">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>
                
                @if(isset($categoryStatus))
                <h2 class="text-xl font-semibold mb-4 mt-6">Status by Category</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 shadow rounded">
                        <h3 class="font-semibold mb-2">PCs</h3>
                        <div class="text-sm">Working: {{ $categoryStatus['pc']['Working'] ?? 0 }}</div>
                        <div class="text-sm">Not Working: {{ $categoryStatus['pc']['Not working'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 shadow rounded">
                        <h3 class="font-semibold mb-2">Accessories</h3>
                        <div class="text-sm">Working: {{ $categoryStatus['accessory']['Working'] ?? 0 }}</div>
                        <div class="text-sm">Not Working: {{ $categoryStatus['accessory']['Not working'] ?? 0 }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 shadow rounded">
                        <h3 class="font-semibold mb-2">Network Devices</h3>
                        <div class="text-sm">Working: {{ $categoryStatus['network']['Working'] ?? 0 }}</div>
                        <div class="text-sm">Not Working: {{ $categoryStatus['network']['Not working'] ?? 0 }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
