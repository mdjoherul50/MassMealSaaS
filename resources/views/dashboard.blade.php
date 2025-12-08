<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Deposits (Meals)</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalDeposits, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Bazar</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalBazar, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Meal Rate</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($avgMealRate, 4) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Meal Net ({{ $mealNet >= 0 ? 'Extra' : 'Minus' }})</h3>
                        <p class="mt-2 text-2xl font-semibold {{ $mealNet >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($mealNet, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">House Rent Provider Total</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            {{ $rentMain ? number_format((float) $rentMain->total, 2) : '0.00' }}
                        </p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Assigned to Members</h3>
                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            {{ $rentMain ? number_format((float) $rentMain->assigned_to_members, 2) : '0.00' }}
                        </p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Rent Remaining</h3>
                        <p class="mt-2 text-2xl font-semibold {{ $rentMain && $rentMain->remaining_balance >= 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $rentMain ? number_format((float) $rentMain->remaining_balance, 2) : '0.00' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-wrap gap-4">
                    <a href="{{ route('reports.overview') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        {{ __('View Monthly Overview') }}
                    </a>
                    @can('houserent.manage')
                        <a href="{{ route('house-rent-mains.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            {{ __('Manage House Rent') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
