<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Total Requests</div>
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-yellow-700 text-sm">Pending</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-blue-700 text-sm">Assigned</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $stats['assigned'] }}</div>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-green-700 text-sm">Fixed</div>
                    <div class="text-2xl font-bold text-green-900">{{ $stats['fixed'] }}</div>
                </div>
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-red-700 text-sm">Not Fixed</div>
                    <div class="text-2xl font-bold text-red-900">{{ $stats['not_fixed'] }}</div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Requests</h3>
                        <a href="{{ route('user.requests.create') }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                            Submit New Request
                        </a>
                    </div>

                    @if($recentRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->request_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->unit }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($request->status === 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($request->status === 'Technician Assigned') bg-blue-100 text-blue-800
                                                    @elseif($request->status === 'Fixed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $request->technician ? $request->technician->name : 'Not assigned' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.requests.index') }}" wire:navigate class="text-indigo-600 hover:text-indigo-800 text-sm">View all requests →</a>
                        </div>
                    @else
                        <p class="text-gray-500">No requests yet. <a href="{{ route('user.requests.create') }}" wire:navigate class="text-indigo-600 hover:text-indigo-800">Submit your first request</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
