<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fa-solid fa-chart-line mr-2"></i>
                {{ __('common.dashboard') }}
            </h2>
            <div class="text-sm text-gray-600">
                <i class="fa-solid fa-calendar mr-1"></i>
                {{ now()->format('F Y') }}
            </div>
        </div>
    </x-slot>

    @php
        $currentMonth = now()->format('Y-m');
        $startDate = \Carbon\Carbon::parse($currentMonth . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $totalBazar = \App\Models\Bazar::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $totalMeal = \App\Models\Meal::whereBetween('date', [$startDate, $endDate])
            ->sum(\Illuminate\Support\Facades\DB::raw('breakfast + lunch + dinner'));
        $avgMealRate = $totalMeal > 0 ? $totalBazar / $totalMeal : 0;
        $totalDeposits = \App\Models\Deposit::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $mealNet = $totalDeposits - $totalBazar;

        $rentMain = \App\Models\HouseRentMain::where('month', $currentMonth)->first();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Meal Statistics -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-utensils mr-2"></i>
                    {{ __('Meal Statistics') ?? 'Meal Statistics' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('Total Deposits') ?? 'Total Deposits' }}</h3>
                                    <p class="mt-2 text-3xl font-bold">৳{{ number_format($totalDeposits, 2) }}</p>
                                </div>
                                <i class="fa-solid fa-wallet text-4xl opacity-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('Total Bazar') ?? 'Total Bazar' }}</h3>
                                    <p class="mt-2 text-3xl font-bold">৳{{ number_format($totalBazar, 2) }}</p>
                                </div>
                                <i class="fa-solid fa-shopping-cart text-4xl opacity-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('Meal Rate') ?? 'Meal Rate' }}</h3>
                                    <p class="mt-2 text-3xl font-bold">৳{{ number_format($avgMealRate, 2) }}</p>
                                    <p class="text-xs opacity-75 mt-1">{{ number_format($totalMeal) }} {{ __('meals') ?? 'meals' }}</p>
                                </div>
                                <i class="fa-solid fa-calculator text-4xl opacity-20"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-{{ $mealNet >= 0 ? 'teal' : 'red' }}-500 to-{{ $mealNet >= 0 ? 'teal' : 'red' }}-600 overflow-hidden shadow-lg sm:rounded-lg">
                        <div class="p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium uppercase tracking-wider opacity-90">{{ __('Balance') ?? 'Balance' }}</h3>
                                    <p class="mt-2 text-3xl font-bold">৳{{ number_format(abs($mealNet), 2) }}</p>
                                    <p class="text-xs opacity-75 mt-1">{{ $mealNet >= 0 ? __('Extra') ?? 'Extra' : __('Deficit') ?? 'Deficit' }}</p>
                                </div>
                                <i class="fa-solid fa-{{ $mealNet >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }} text-4xl opacity-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- House Rent Statistics -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-house mr-2"></i>
                    {{ __('house_rent.house_rent_management') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-l-4 border-orange-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ __('house_rent.total_rent') }}</h3>
                                    <p class="mt-2 text-2xl font-bold text-gray-900">
                                        ৳{{ $rentMain ? number_format((float) $rentMain->total, 2) : '0.00' }}
                                    </p>
                                </div>
                                <i class="fa-solid fa-building text-3xl text-orange-500 opacity-30"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-l-4 border-blue-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ __('house_rent.assigned_to_members') }}</h3>
                                    <p class="mt-2 text-2xl font-bold text-gray-900">
                                        ৳{{ $rentMain ? number_format((float) $rentMain->assigned_to_members, 2) : '0.00' }}
                                    </p>
                                </div>
                                <i class="fa-solid fa-users text-3xl text-blue-500 opacity-30"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border-l-4 border-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'red' : 'green' }}-500">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ __('house_rent.remaining_balance') }}</h3>
                                    <p class="mt-2 text-2xl font-bold {{ $rentMain && $rentMain->remaining_balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        ৳{{ $rentMain ? number_format(abs((float) $rentMain->remaining_balance), 2) : '0.00' }}
                                    </p>
                                </div>
                                <i class="fa-solid fa-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'exclamation-triangle' : 'check-circle' }} text-3xl text-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'red' : 'green' }}-500 opacity-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fa-solid fa-bolt mr-2"></i>
                        {{ __('Quick Actions') ?? 'Quick Actions' }}
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('reports.overview') }}" class="inline-flex items-center px-5 py-3 bg-white text-indigo-600 text-sm font-semibold rounded-lg hover:bg-gray-100 shadow-md transition-all">
                            <i class="fa-solid fa-chart-pie mr-2"></i>
                            {{ __('Monthly Report') ?? 'Monthly Report' }}
                        </a>
                        @can('meals.manage')
                            <a href="{{ route('meals.bulkEntry') }}" class="inline-flex items-center px-5 py-3 bg-white text-purple-600 text-sm font-semibold rounded-lg hover:bg-gray-100 shadow-md transition-all">
                                <i class="fa-solid fa-utensils mr-2"></i>
                                {{ __('Add Meals') ?? 'Add Meals' }}
                            </a>
                        @endcan
                        @can('bazars.manage')
                            <a href="{{ route('bazars.create') }}" class="inline-flex items-center px-5 py-3 bg-white text-green-600 text-sm font-semibold rounded-lg hover:bg-gray-100 shadow-md transition-all">
                                <i class="fa-solid fa-shopping-cart mr-2"></i>
                                {{ __('Add Bazar') ?? 'Add Bazar' }}
                            </a>
                        @endcan
                        @can('houserent.manage')
                            <a href="{{ route('house-rent-mains.index') }}" class="inline-flex items-center px-5 py-3 bg-white text-orange-600 text-sm font-semibold rounded-lg hover:bg-gray-100 shadow-md transition-all">
                                <i class="fa-solid fa-house mr-2"></i>
                                {{ __('Manage Rent') ?? 'Manage Rent' }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
