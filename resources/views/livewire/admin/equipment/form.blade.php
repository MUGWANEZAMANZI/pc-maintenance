<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $equipmentId ? 'Edit' : 'Add' }} 
            @if($equipment_type === 'pc') PC
            @elseif($equipment_type === 'accessory') Accessory
            @else Network Device
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Device Name -->
                            <div class="md:col-span-2">
                                <label for="device_name" class="block text-sm font-medium text-gray-700">Device Name</label>
                                <input type="text" wire:model="device_name" id="device_name" 
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('device_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Brand -->
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                                <input type="text" wire:model="brand" id="brand" 
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('brand') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Registration Year -->
                            <div>
                                <label for="registration_year" class="block text-sm font-medium text-gray-700">Registration Year</label>
                                <input type="number" wire:model="registration_year" id="registration_year" 
                                       class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @error('registration_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Health Status -->
                            <div>
                                <label for="health" class="block text-sm font-medium text-gray-700">Health Status</label>
                                <select wire:model="health" id="health" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="healthy">Healthy</option>
                                    <option value="malfunctioning">Malfunctioning</option>
                                    <option value="dead">Dead</option>
                                </select>
                                @error('health') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Building -->
                            <div>
                                <label for="building_id" class="block text-sm font-medium text-gray-700">Building</label>
                                <select wire:model="building_id" id="building_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select Building</option>
                                    @foreach($buildings as $building)
                                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                                    @endforeach
                                </select>
                                @error('building_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                                <select wire:model.live="department_id" id="department_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Computer Lab -->
                            <div>
                                <label for="computer_lab_id" class="block text-sm font-medium text-gray-700">Computer Lab</label>
                                <select wire:model="computer_lab_id" id="computer_lab_id" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        @if(empty($department_id)) disabled @endif>
                                    <option value="">Select Computer Lab</option>
                                    @foreach($computerLabs as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                    @endforeach
                                </select>
                                @error('computer_lab_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            @if($equipment_type === 'pc')
                                <!-- PC Specific Fields -->
                                <div class="md:col-span-2">
                                    <label for="specifications" class="block text-sm font-medium text-gray-700">Specifications</label>
                                    <textarea wire:model="specifications" id="specifications" rows="3"
                                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                    @error('specifications') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="hdd" class="block text-sm font-medium text-gray-700">HDD</label>
                                    <input type="text" wire:model="hdd" id="hdd" 
                                           class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @error('hdd') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="ram" class="block text-sm font-medium text-gray-700">RAM</label>
                                    <input type="text" wire:model="ram" id="ram" 
                                           class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @error('ram') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="os" class="block text-sm font-medium text-gray-700">Operating System</label>
                                    <input type="text" wire:model="os" id="os" 
                                           class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @error('os') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <!-- Accessory/Network Device Specific -->
                                <div class="md:col-span-2">
                                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                    <input type="text" wire:model="type" id="type" 
                                           class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.equipment.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ $equipmentId ? 'Update' : 'Create' }} Equipment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
