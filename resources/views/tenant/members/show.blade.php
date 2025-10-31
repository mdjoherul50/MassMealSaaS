<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Statement') }}: {{ $member->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('members.show', $member) }}" class="flex items-center">
                        <x-input-label for="month" :value="__('Select Month')" class="mr-2" />
                        <x-text-input id="month" type="month" name="month" :value="$selectedMonth" required />
                        <x-primary-button class="ms-2">
                            {{ __('Load Statement') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">Total Deposit</h3>
                        <p class="mt-1 text-3xl font-semibold text-green-600">{{ number_format($memberTotalDeposit, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">Total Meal</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($memberTotalMeal, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">Meal Rate</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($avgMealRate, 4) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">Total Charge</h3>
                        <p class="mt-1 text-3xl font-semibold text-red-600">{{ number_format($memberTotalCharge, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1 md:col-span-4">
                    <div class="p-6 text-center text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">Current Balance</h3>
                        <p class="mt-1 text-4xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($balance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Date-wise Meals ({{ $selectedMonth }})</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">B</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">L</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">D</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dateWiseMeals as $meal)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($meal->date)->format('d M') }}</td>
                                        <td class="px-4 py-2 text-center">{{ $meal->breakfast }}</td>
                                        <td class="px-4 py-2 text-center">{{ $meal->lunch }}</td>
                                        <td class="px-4 py-2 text-center">{{ $meal->dinner }}</td>
                                        <td class="px-4 py-2 text-right font-medium">{{ $meal->breakfast + $meal->lunch + $meal->dinner }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No meals found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Date-wise Deposits ({{ $selectedMonth }})</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dateWiseDeposits as $deposit)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($deposit->date)->format('d M') }}</td>
                                        <td class="px-4 py-2 text-right font-medium">{{ number_format($deposit->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-4 py-4 text-center text-gray-500">No deposits found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

