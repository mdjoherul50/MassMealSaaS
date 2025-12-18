<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('tenant.tenant_management') }}
            </h2>
            <div class="flex gap-3">
                <x-language-switcher />
                <a href="{{ route('superadmin.tenants.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('tenant.add_tenant') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" action="{{ route('superadmin.tenants.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.search') }}..." class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">{{ __('common.status') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('common.suspended') }}</option>
                            </select>
                        </div>
                        <div>
                            <select name="subscription_status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="">{{ __('common.subscription_status') }}</option>
                                <option value="trial" {{ request('subscription_status') == 'trial' ? 'selected' : '' }}>{{ __('common.trial') }}</option>
                                <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                <option value="expired" {{ request('subscription_status') == 'expired' ? 'selected' : '' }}>{{ __('common.expired') }}</option>
                                <option value="cancelled" {{ request('subscription_status') == 'cancelled' ? 'selected' : '' }}>{{ __('common.cancelled') }}</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('superadmin.tenants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.clear') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('tenant.tenant_name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('tenant.owner') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('plan.plan') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.status') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.subscription') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.created_at') }}</th>
                                <th class="relative px-6 py-3"><span class="sr-only">{{ __('common.actions') }}</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tenants as $tenant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                        @if($tenant->phone)
                                            <div class="text-sm text-gray-500">{{ $tenant->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $tenant->owner->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $tenant->owner->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $tenant->planDetails->name ?? 'N/A' }}</div>
                                        @if($tenant->planDetails)
                                            <div class="text-sm text-gray-500">৳{{ number_format($tenant->planDetails->price_monthly, 0) }}/{{ __('plan.monthly') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tenant->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $tenant->status == 'active' ? __('common.active') : __('common.suspended') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($tenant->subscription_status == 'trial')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ __('common.trial') }} ({{ $tenant->remainingTrialDays() }} {{ __('common.days') }})
                                            </span>
                                        @elseif($tenant->subscription_status == 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ __('common.active') }}
                                            </span>
                                        @elseif($tenant->subscription_status == 'expired')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ __('common.expired') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($tenant->subscription_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $tenant->created_at->format('d M, Y') }}</div>
                                        @if($tenant->plan_expires_at)
                                            <div class="text-sm text-gray-500">{{ __('common.expires') }}: {{ $tenant->plan_expires_at->format('d M, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="text-blue-600 hover:text-blue-900 mr-3">{{ __('common.view') }}</a>
                                        <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('common.edit') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        {{ __('common.no_data') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $tenants->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>