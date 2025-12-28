<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tenant.tenant_details') }}: {{ $tenant->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">{{ __('tenant.tenant_info') }}</h3>
                    <p><strong>{{ __('tenant.owner') }}:</strong> {{ $tenant->owner->name ?? __('common.na') }} ({{ $tenant->owner->email ?? '' }})</p>
                    <p><strong>{{ __('plan.plan') }}:</strong> {{ $tenant->plan }}</p>
                    <p><strong>{{ __('tenant.registered') }}:</strong> {{ $tenant->created_at->format('d M, Y') }}</p>
                    <p><strong>{{ __('tenant.total_members') }}:</strong> {{ $tenant->members->count() }}</p>
                    <p><strong>{{ __('tenant.total_users') }}:</strong> {{ $tenant->users->count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">{{ __('tenant.update_status') }}</h3>
                    <form action="{{ route('superadmin.tenants.update', $tenant) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('common.status')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="active" {{ $tenant->status == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                <option value="suspended" {{ $tenant->status == 'suspended' ? 'selected' : '' }}>{{ __('common.suspended') }}</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('common.update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>