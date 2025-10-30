<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant Details') }}: {{ $tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Tenant Info</h3>
                    <p><strong>Owner:</strong> {{ $tenant->owner->name ?? 'N/A' }} ({{ $tenant->owner->email ?? '' }})</p>
                    <p><strong>Plan:</strong> {{ $tenant->plan }}</p>
                    <p><strong>Registered:</strong> {{ $tenant->created_at->format('d M, Y') }}</p>
                    <p><strong>Total Members:</strong> {{ $tenant->members->count() }}</p>
                    <p><strong>Total Users:</strong> {{ $tenant->users->count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Update Status</h3>
                    <form action="{{ route('superadmin.tenants.update', $tenant) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="active" {{ $tenant->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ $tenant->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update Status') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>