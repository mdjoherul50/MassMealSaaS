<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('plan.plan_details') }}
            </h2>
            <div class="flex gap-3">
                <x-language-switcher />
                <a href="{{ route('superadmin.plans.edit', $plan) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.edit') }}
                </a>
                <a href="{{ route('superadmin.plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Plan Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $plan->slug }}</p>
                        </div>
                        <div class="flex gap-2">
                            @if($plan->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ __('common.active') }}
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ __('common.inactive') }}
                                </span>
                            @endif
                            @if($plan->is_popular)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ __('plan.is_popular') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($plan->description)
                        <p class="text-gray-700 mb-6">{{ $plan->description }}</p>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Monthly Price -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">{{ __('plan.price_per_month') }}</p>
                            <p class="text-2xl font-bold text-gray-900">৳{{ number_format($plan->price_monthly, 2) }}</p>
                        </div>

                        <!-- Yearly Price -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">{{ __('plan.price_per_year') }}</p>
                            <p class="text-2xl font-bold text-gray-900">৳{{ number_format($plan->price_yearly, 2) }}</p>
                        </div>

                        <!-- Max Members -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">{{ __('plan.max_members') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $plan->max_members }}</p>
                        </div>

                        <!-- Storage -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">{{ __('plan.max_storage') }}</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $plan->max_storage_mb }} MB</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Trial Days -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-600">{{ __('plan.trial_days') }}</p>
                            <p class="text-xl font-bold text-blue-900">{{ $plan->trial_days }} {{ __('common.days') }}</p>
                        </div>

                        <!-- Sort Order -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">{{ __('plan.sort_order') }}</p>
                            <p class="text-xl font-bold text-gray-900">{{ $plan->sort_order }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Card -->
            @if($plan->features && count($plan->features) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('plan.features') }}</h3>
                        <ul class="space-y-2">
                            @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Tenants Using This Plan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ __('tenant.tenants') }} ({{ $plan->tenants->count() }})
                    </h3>
                    
                    @if($plan->tenants->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('tenant.tenant_name') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.subscription') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.created_at') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($plan->tenants as $tenant)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $tenant->owner->name ?? __('common.na') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tenant->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $tenant->status == 'active' ? __('common.active') : __('common.suspended') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($tenant->subscription_status == 'trial')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ __('common.trial') }}
                                                    </span>
                                                @elseif($tenant->subscription_status == 'active')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ __('common.active') }}
                                                    </span>
                                                @elseif($tenant->subscription_status == 'expired')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        {{ __('common.expired') }}
                                                    </span>
                                                @elseif($tenant->subscription_status == 'cancelled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ __('common.cancelled') }}
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ __('common.na') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $tenant->created_at->format('d M, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ __('common.view') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">{{ __('common.no_data') }}</p>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">{{ __('common.created_at') }}:</span>
                            {{ $plan->created_at->format('d M, Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">{{ __('common.updated_at') }}:</span>
                            {{ $plan->updated_at->format('d M, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
