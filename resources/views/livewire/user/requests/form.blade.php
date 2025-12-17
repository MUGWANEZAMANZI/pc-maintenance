<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit New Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label for="equipment_type" class="block text-sm font-medium text-gray-700">Equipment Type *</label>
                            <select wire:model.live="equipment_type" id="equipment_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Equipment Type</option>
                                <option value="pc">PC/Computer</option>
                                <option value="accessory">Accessory</option>
                                <option value="network_device">Network Device</option>
                                <option value="general">General/Lab Issue</option>
                            </select>
                            @error('equipment_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if($equipment_type && $equipment_type !== 'general')
                            <div class="mb-4">
                                <label for="building_id" class="block text-sm font-medium text-gray-700">Building *</label>
                                <select wire:model.live="building_id" id="building_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Building</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                                    @endforeach
                                </select>
                                @error('building_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="computer_lab_id" class="block text-sm font-medium text-gray-700">Computer Lab *</label>
                                <select wire:model.live="computer_lab_id" id="computer_lab_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @disabled="!$building_id">
                                    <option value="">Select Computer Lab</option>
                                    @foreach($computerLabs as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->name }} - {{ $lab->location ?? 'No location' }}</option>
                                    @endforeach
                                </select>
                                @error('computer_lab_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($equipment_type === 'pc' && $computer_lab_id && $pcs->count() > 0)
                            <div class="mb-4">
                                <label for="pc_id" class="block text-sm font-medium text-gray-700">Select PC *</label>
                                <select wire:model="pc_id" id="pc_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Choose a PC</option>
                                    @foreach($pcs as $pc)
                                        <option value="{{ $pc->id }}">[ID {{ $pc->id }}] {{ $pc->device_name ?? ($pc->brand.' '.$pc->os) }} - {{ $pc->computerLab->name ?? 'No lab' }}</option>
                                    @endforeach
                                </select>
                                @error('pc_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($equipment_type === 'accessory' && $computer_lab_id && $accessories->count() > 0)
                            <div class="mb-4">
                                <label for="accessory_id" class="block text-sm font-medium text-gray-700">Select Accessory *</label>
                                <select wire:model="accessory_id" id="accessory_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Choose an Accessory</option>
                                    @foreach($accessories as $accessory)
                                        <option value="{{ $accessory->id }}">[ID {{ $accessory->id }}] {{ $accessory->device_name ?? ($accessory->type.' - '.$accessory->brand) }} - {{ $accessory->computerLab->name ?? 'No lab' }}</option>
                                    @endforeach
                                </select>
                                @error('accessory_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($equipment_type === 'network_device' && $computer_lab_id && $networkDevices->count() > 0)
                            <div class="mb-4">
                                <label for="network_device_id" class="block text-sm font-medium text-gray-700">Select Network Device *</label>
                                <select wire:model="network_device_id" id="network_device_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Choose a Network Device</option>
                                    @foreach($networkDevices as $device)
                                        <option value="{{ $device->id }}">[ID {{ $device->id }}] {{ $device->device_name ?? ($device->type.' - '.$device->brand) }} - {{ $device->computerLab->name ?? 'No lab' }}</option>
                                    @endforeach
                                </select>
                                @error('network_device_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="request_type" class="block text-sm font-medium text-gray-700">Request Type *</label>
                            <input type="text" wire:model="request_type" id="request_type" 
                                   placeholder="e.g., PC Not Booting, Printer Error, Network Down"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('request_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea wire:model="description" id="description" rows="4"
                                      placeholder="Please describe the problem in detail..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="telephone" class="block text-sm font-medium text-gray-700">Contact Telephone *</label>
                            <input type="text" wire:model="telephone" id="telephone" 
                                   placeholder="e.g., +250 123 456 789"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('telephone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('user.requests.index') }}" wire:navigate 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
