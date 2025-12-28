<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                        <i class="fa-solid fa-building-user text-white text-lg"></i>
                    </div>
                    {{ __('tenant.tenant_management') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 ml-13">{{ __('tenant.manage_all_tenants') }}</p>
            </div>
            <div class="flex gap-3">
                <button onclick="exportTenants('csv')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-green-700 transition-all shadow-sm">
                    <i class="fa-solid fa-file-csv mr-2"></i>
                    {{ __('common.export_csv') }}
                </button>
                <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg">
                    <i class="fa-solid fa-plus mr-2"></i>
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-building text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('common.total_tenants') }}</h3>
                    <p class="text-3xl font-bold mt-2">{{ $tenants->total() }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-check-circle text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('common.active_tenants') }}</h3>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Tenant::where('status', 'active')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-clock text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('common.trial_tenants') }}</h3>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Tenant::where('subscription_status', 'trial')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fa-solid fa-ban text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('common.suspended') }}</h3>
                    <p class="text-3xl font-bold mt-2">{{ \App\Models\Tenant::where('status', 'suspended')->count() }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-6">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <i class="fa-solid fa-filter text-indigo-600 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('common.filters') }}</h3>
                    </div>
                    <form method="GET" action="{{ route('superadmin.tenants.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('common.search') }}</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('tenant.search_placeholder') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('common.status') }}</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('common.all_status') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>{{ __('common.suspended') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('common.subscription_status') }}</label>
                            <select name="subscription_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('common.all_subscriptions') }}</option>
                                <option value="trial" {{ request('subscription_status') == 'trial' ? 'selected' : '' }}>{{ __('common.trial') }}</option>
                                <option value="active" {{ request('subscription_status') == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                <option value="expired" {{ request('subscription_status') == 'expired' ? 'selected' : '' }}>{{ __('common.expired') }}</option>
                                <option value="cancelled" {{ request('subscription_status') == 'cancelled' ? 'selected' : '' }}>{{ __('common.cancelled') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ __('plan.plan') }}</label>
                            <select name="plan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('common.all_plans') }}</option>
                                @foreach(\App\Models\Plan::all() as $plan)
                                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2 items-end">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                                <i class="fa-solid fa-search mr-2"></i>
                                {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('superadmin.tenants.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all">
                                <i class="fa-solid fa-times mr-2"></i>
                                {{ __('common.clear') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-6" x-data="{ selectedTenants: [], selectAll: false }">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" x-model="selectAll" @change="selectedTenants = selectAll ? {{ $tenants->pluck('id') }} : []" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-3 text-sm font-medium text-gray-700" x-text="selectedTenants.length > 0 ? selectedTenants.length + ' {{ __('common.selected') }}' : '{{ __('common.select_all') }}'"></span>
                    </div>
                    <div class="flex gap-2" x-show="selectedTenants.length > 0">
                        <button onclick="bulkAction('activate')" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-all">
                            <i class="fa-solid fa-check mr-1"></i>
                            {{ __('common.activate') }}
                        </button>
                        <button onclick="bulkAction('suspend')" class="inline-flex items-center px-3 py-1.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-all">
                            <i class="fa-solid fa-ban mr-1"></i>
                            {{ __('common.suspend') }}
                        </button>
                        <button onclick="bulkAction('delete')" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-all">
                            <i class="fa-solid fa-trash mr-1"></i>
                            {{ __('common.delete') }}
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" x-model="selectAll" class="rounded border-gray-300 text-indigo-600">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('tenant.tenant_name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('tenant.owner') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('plan.plan') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.status') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.subscription') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.created_at') }}</th>
                                <th class="relative px-6 py-3"><span class="sr-only">{{ __('common.actions') }}</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tenants as $tenant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" :value="{{ $tenant->id }}" x-model="selectedTenants" class="rounded border-gray-300 text-indigo-600">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 text-white font-semibold text-sm">
                                                {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                                @if($tenant->phone)
                                                    <div class="text-xs text-gray-500"><i class="fa-solid fa-phone mr-1"></i>{{ $tenant->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $tenant->owner->name ?? __('common.na') }}</div>
                                        <div class="text-sm text-gray-500">{{ $tenant->owner->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $tenant->planDetails->name ?? __('common.na') }}</div>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $tenant->created_at->format('d M, Y') }}</div>
                                        @if($tenant->plan_expires_at)
                                            <div class="text-sm text-gray-500">{{ __('common.expires') }}: {{ $tenant->plan_expires_at->format('d M, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all" title="{{ __('common.view') }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-all" title="{{ __('common.edit') }}">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <i class="fa-solid fa-inbox text-6xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 text-lg">{{ __('common.no_data') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportTenants(format) {
            const params = new URLSearchParams(window.location.search);
            params.set('export', format);
            window.location.href = '{{ route("superadmin.tenants.index") }}?' + params.toString();
        }

        function bulkAction(action) {
            const selectedTenants = Alpine.store('selectedTenants') || [];
            if (selectedTenants.length === 0) {
                alert('{{ __('common.select_tenants_first') }}');
                return;
            }
            
            if (confirm(`{{ __('common.confirm_bulk_action') }} ${action}?`)) {
                // Implement bulk action API call here
                console.log('Bulk action:', action, 'for tenants:', selectedTenants);
            }
        }
    </script>
</x-app-layout>