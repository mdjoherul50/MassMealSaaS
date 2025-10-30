<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monthly Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('reports.overview') }}" class="flex items-center">
                        <x-input-label for="month" :value="__('Select Month')" class="mr-2" />
                        <x-text-input id="month" type="month" name="month" :value="$selectedMonth" required />
                        <x-primary-button class="ms-2">
                            {{ __('Load Report') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">Total Bazar</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalBazar, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">Total Meal</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalMeal, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">Meal Rate</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($avgMealRate, 4) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Meal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Charge</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reportData as $index => $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($data['total_meal'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($data['total_deposit'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-red-600">{{ number_format($data['total_charge'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $data['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($data['balance'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No data found for this month.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>