<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tenant.member_statement') }}: {{ $member->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Member Profile Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-6">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Profile Photo -->
                        <div class="flex-shrink-0">
                            @if($member->user && $member->user->profile_photo)
                                <img src="{{ $member->user->profile_photo_url }}" alt="{{ $member->name }}" class="w-48 h-48 rounded-2xl object-cover shadow-lg border-4 border-indigo-100">
                            @else
                                <div class="w-48 h-48 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold text-6xl shadow-lg">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                            @endif
                            @if($member->user && $member->user->is_online)
                                <div class="mt-3 flex items-center justify-center gap-2">
                                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                                    <span class="text-sm font-medium text-green-600">{{ __('chat.online') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Personal Information -->
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $member->name }}</h3>
                            <p class="text-lg text-gray-600 mb-6">{{ $member->user?->role?->name ?? 'Member' }}</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-envelope text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-medium">{{ __('common.email') }}</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $member->email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-phone text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-medium">{{ __('common.phone') }}</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $member->phone ?? __('common.na') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-calendar text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-medium">{{ __('common.joined') }}</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $member->join_date ? \Carbon\Carbon::parse($member->join_date)->format('d M, Y') : __('common.na') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-id-card text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-medium">{{ __('common.member_id') }}</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $member->reg_id ?? '#' . $member->id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('members.show', $member) }}" class="flex items-center">
                        <x-input-label for="month" :value="__('common.select_month')" class="mr-2" />
                        <x-text-input id="month" type="month" name="month" :value="$selectedMonth" required />
                        <x-primary-button class="ms-2">
                            {{ __('tenant.load_statement') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">{{ __('common.total_deposits') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-green-600">{{ number_format($memberTotalDeposit, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">{{ __('common.total_meal') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($memberTotalMeal, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">{{ __('common.meal_rate') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($avgMealRate, 4) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">{{ __('common.total_charge') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-red-600">{{ number_format($memberTotalCharge, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg col-span-1 md:col-span-4">
                    <div class="p-6 text-center text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase">{{ __('common.current_balance') }}</h3>
                        <p class="mt-1 text-4xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($balance, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">{{ __('tenant.date_wise_meals') }} ({{ $selectedMonth }})</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.date') }}</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.breakfast_short') }}</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.lunch_short') }}</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.dinner_short') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.total') }}</th>
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
                                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">{{ __('tenant.no_meals_found') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">{{ __('tenant.date_wise_deposits') }} ({{ $selectedMonth }})</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.date') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.amount') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dateWiseDeposits as $deposit)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($deposit->date)->format('d M') }}</td>
                                        <td class="px-4 py-2 text-right font-medium">{{ number_format($deposit->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-4 py-4 text-center text-gray-500">{{ __('tenant.no_deposits_found') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

