<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                        <i class="fa-solid fa-crown text-white text-lg"></i>
                    </div>
                    {{ __('common.super_admin_dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 ml-13">{{ __('common.welcome_back') }}, {{ Auth::user()->name }}</p>
            </div>
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-2 rounded-lg border border-indigo-100">
                <div class="text-xs text-gray-500 uppercase tracking-wide">{{ __('common.system_overview') }}</div>
                <div class="text-sm font-semibold text-gray-900 mt-0.5">
                    <i class="fa-solid fa-server mr-1 text-indigo-600"></i>
                    {{ __('common.platform_stats') }}
                </div>
            </div>
        </div>
    </x-slot>

    @php
        $totalTenants = \App\Models\Tenant::count();
        $activeTenants = \App\Models\Tenant::where('status', 'active')->count();
        $suspendedTenants = \App\Models\Tenant::where('status', 'suspended')->count();
        $totalPlans = \App\Models\Plan::count();
        $activePlans = \App\Models\Plan::where('is_active', true)->count();
        $totalUsers = \App\Models\User::count();
        $recentTenants = \App\Models\Tenant::latest()->take(5)->get();
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Platform Statistics -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa-solid fa-chart-line text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ __('common.platform_statistics') }}
                        </h3>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            {{ __('common.live') }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Tenants -->
                    <div class="group bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <i class="fa-solid fa-building text-2xl"></i>
                                    </div>
                                    <div class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                        {{ __('common.total') }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-medium uppercase tracking-wider opacity-90 mb-2">{{ __('tenant.tenants') }}</h3>
                                <p class="text-3xl font-bold">{{ number_format($totalTenants) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Active Tenants -->
                    <div class="group bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <i class="fa-solid fa-check-circle text-2xl"></i>
                                    </div>
                                    <div class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                        {{ __('common.active') }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-medium uppercase tracking-wider opacity-90 mb-2">{{ __('common.active_tenants') }}</h3>
                                <p class="text-3xl font-bold">{{ number_format($activeTenants) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Plans -->
                    <div class="group bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <i class="fa-solid fa-crown text-2xl"></i>
                                    </div>
                                    <div class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                        {{ __('plan.subscription') }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-medium uppercase tracking-wider opacity-90 mb-2">{{ __('plan.plans') }}</h3>
                                <p class="text-3xl font-bold">{{ number_format($totalPlans) }}</p>
                                <p class="text-xs opacity-90 mt-2 flex items-center">
                                    <i class="fa-solid fa-check mr-1"></i>
                                    {{ number_format($activePlans) }} {{ __('common.active') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Users -->
                    <div class="group bg-gradient-to-br from-orange-500 to-orange-600 overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                        <i class="fa-solid fa-users text-2xl"></i>
                                    </div>
                                    <div class="text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                        {{ __('common.platform') }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-medium uppercase tracking-wider opacity-90 mb-2">{{ __('common.total_users') }}</h3>
                                <p class="text-3xl font-bold">{{ number_format($totalUsers) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tenants -->
            <div>
                <div class="flex items-center mb-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa-solid fa-building-user text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ __('common.recent_tenants') }}
                        </h3>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('plan.plan') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.created_at') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentTenants as $tenant)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 text-white font-semibold">
                                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $tenant->domain }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fa-solid fa-crown mr-1"></i>
                                                {{ $tenant->plan?->name ?? __('common.na') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($tenant->status === 'active') bg-green-100 text-green-800
                                                @elseif($tenant->status === 'suspended') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($tenant->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $tenant->created_at->format('d M, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('superadmin.tenants.show', $tenant->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fa-solid fa-inbox text-4xl mb-2 text-gray-300"></i>
                                            <p>{{ __('common.no_data') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 overflow-hidden shadow-2xl sm:rounded-2xl relative">
                <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                <div class="p-8 relative z-10">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                            <i class="fa-solid fa-bolt text-white text-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white">
                            {{ __('common.quick_actions') }}
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('superadmin.tenants.index') }}" class="group flex items-center px-5 py-4 bg-white text-indigo-600 text-sm font-semibold rounded-xl hover:bg-indigo-50 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-200 transition-colors">
                                <i class="fa-solid fa-building-user text-lg"></i>
                            </div>
                            <span>{{ __('common.manage_tenants') }}</span>
                        </a>
                        <a href="{{ route('superadmin.plans.index') }}" class="group flex items-center px-5 py-4 bg-white text-purple-600 text-sm font-semibold rounded-xl hover:bg-purple-50 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                                <i class="fa-solid fa-crown text-lg"></i>
                            </div>
                            <span>{{ __('plan.manage_plans') }}</span>
                        </a>
                        @can('roles.manage')
                            <a href="{{ route('superadmin.roles.index') }}" class="group flex items-center px-5 py-4 bg-white text-green-600 text-sm font-semibold rounded-xl hover:bg-green-50 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                    <i class="fa-solid fa-shield-halved text-lg"></i>
                                </div>
                                <span>{{ __('common.manage_roles') }}</span>
                            </a>
                        @endcan
                        <a href="{{ route('superadmin.branding.logo.edit') }}" class="group flex items-center px-5 py-4 bg-white text-orange-600 text-sm font-semibold rounded-xl hover:bg-orange-50 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition-colors">
                                <i class="fa-solid fa-image text-lg"></i>
                            </div>
                            <span>{{ __('common.site_logo') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
