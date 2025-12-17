<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requests & Equipment Health') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Equipment Health Map -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Equipment Health Map</h3>
                        <button wire:click="toggleHealthMap" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ $showHealthMap ? 'Hide Map' : 'Show Map' }}
                        </button>
                    </div>

                    @if($showHealthMap)
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @forelse($equipment as $item)
                                @php
                                    $bgColor = match($item->health) {
                                        'healthy' => 'bg-green-500',
                                        'malfunctioning' => 'bg-yellow-500',
                                        'dead' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                    $typeSlug = match($item->equipment_type) {
                                        'PC' => 'pc',
                                        'Accessory' => 'accessory',
                                        'Network Device' => 'network_device',
                                    };
                                @endphp
                                <button wire:click="selectEquipment('{{ $typeSlug }}', {{ $item->id }})"
                                        class="{{ $bgColor }} text-white rounded-lg p-4 hover:opacity-90 transition cursor-pointer flex flex-col items-center justify-center min-h-[120px]">
                                    <div class="text-3xl mb-2">
                                        @if($item->equipment_type === 'PC') 💻
                                        @elseif($item->equipment_type === 'Accessory') 🖱️
                                        @else 🌐
                                        @endif
                                    </div>
                                    <div class="text-xs font-semibold text-center">{{ $item->device_name ?? $item->brand }}</div>
                                    <div class="text-xs opacity-90 text-center">{{ $item->computerLab->name ?? 'N/A' }}</div>
                                </button>
                            @empty
                                <div class="col-span-full text-center text-gray-500 py-8">
                                    No equipment registered yet.
                                </div>
                            @endforelse
                        </div>

                        <!-- Legend -->
                        <div class="mt-6 flex justify-center space-x-6 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                                <span>Healthy</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                                <span>Malfunctioning</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                                <span>Dead</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Requests List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">User Requests</h3>
                    <table class="min-w-full bg-white shadow rounded mb-6">
                        <thead>
                        <tr class="text-left text-sm text-gray-600">
                            <th class="p-2">Requester</th>
                            <th class="p-2">Contact</th>
                            <th class="p-2">Unit</th>
                            <th class="p-2">Type</th>
                            <th class="p-2">Description</th>
                            <th class="p-2">Affected Device</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Technician</th>
                            <th class="p-2">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="text-sm">
                        @forelse($requests as $r)
                            <tr class="border-t">
                                <td class="p-2">
                                    {{ $r->first_name }} {{ $r->last_name }}
                                    @if($r->user)
                                        <span class="text-xs text-gray-500">(User)</span>
                                    @else
                                        <span class="text-xs text-gray-500">(Guest)</span>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <div class="text-xs">{{ $r->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $r->telephone }}</div>
                                </td>
                                <td class="p-2">{{ $r->unit }}</td>
                                <td class="p-2">{{ $r->request_type }}</td>
                                <td class="p-2">
                                    <div class="max-w-xs truncate" title="{{ $r->description }}">
                                        {{ $r->description ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="p-2 text-xs">
                                    @if($r->pc)
                                        <div><span class="font-semibold">PC</span> — ID {{ $r->pc->id }}, {{ $r->pc->device_name ?? ($r->pc->brand.' '.$r->pc->os) }}</div>
                                    @elseif($r->accessory)
                                        <div><span class="font-semibold">Accessory</span> — ID {{ $r->accessory->id }}, {{ $r->accessory->device_name ?? ($r->accessory->type.' '.$r->accessory->brand) }}</div>
                                    @elseif($r->networkDevice)
                                        <div><span class="font-semibold">Network</span> — ID {{ $r->networkDevice->id }}, {{ $r->networkDevice->device_name ?? ($r->networkDevice->type.' '.$r->networkDevice->brand) }}</div>
                                    @else
                                        <div class="text-gray-400">General / Not specified</div>
                                    @endif
                                </td>
                                <td class="p-2">
                                    <span class="px-2 py-1 text-xs rounded 
                                        @if($r->status === 'Pending') bg-yellow-100 text-yellow-800
                                        @elseif($r->status === 'Technician Assigned') bg-blue-100 text-blue-800
                                        @elseif($r->status === 'Fixed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $r->status }}
                                    </span>
                                </td>
                                <td class="p-2">{{ optional($r->technician)->name ?? '-' }}</td>
                                <td class="p-2">
                                    @if($r->status === \App\Models\Request::STATUS_PENDING)
                                        <button wire:click="setAssign({{ $r->id }})" class="text-indigo-600 hover:underline">Assign</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="p-4 text-center text-gray-500">No requests found.</td></tr>
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

    <!-- Equipment Details Modal -->
    @if($selectedEquipment)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold">Equipment Details</h3>
                            <button wire:click="closeEquipmentDetails" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="font-medium">Type:</span> {{ $selectedEquipment->equipment_type }}
                            </div>
                            <div>
                                <span class="font-medium">Device Name:</span> {{ $selectedEquipment->device_name ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Brand:</span> {{ $selectedEquipment->brand }}
                            </div>
                            <div>
                                <span class="font-medium">Building:</span> {{ $selectedEquipment->building->name ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Computer Lab:</span> {{ $selectedEquipment->computerLab->name ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Health Status:</span> 
                                <span class="px-2 py-1 text-xs rounded
                                    @if($selectedEquipment->health === 'healthy') bg-green-100 text-green-800
                                    @elseif($selectedEquipment->health === 'malfunctioning') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($selectedEquipment->health) }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium">Assigned Technician:</span> {{ $selectedEquipment->technician->name ?? 'Not assigned' }}
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if(!$selectedEquipment->technician_id)
                            <button wire:click="setAssignEquipment('{{ $selectedEquipment->equipment_type }}', {{ $selectedEquipment->id }})"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Assign to Technician
                            </button>
                        @endif
                        <button wire:click="closeEquipmentDetails" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
