<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.monthly_overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 text-gray-900">
        <form method="GET" action="{{ route('reports.overview') }}" class="flex items-end gap-4">
            <div>
                <x-input-label for="month" :value="__('common.select_month')" />
                <x-text-input id="month" type="month" name="month" :value="$selectedMonth" required />
            </div>

            <div class="min-w-[240px]">
                <x-input-label for="member_id" :value="__('common.filter_by_member')" />
                <select id="member_id" name="member_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    <option value="">{{ __('common.all_members') }}</option>
    @if(isset($members))
        @foreach($members as $m)
            <option value="{{ $m->id }}" {{ (isset($memberId) && (int)$memberId === (int)$m->id) ? 'selected' : '' }}>
                {{ $m->name }}
            </option>
        @endforeach
    @endif
</select>
            </div>

            <x-primary-button>
                {{ __('common.load_report') }}
            </x-primary-button>

            <a
                href="{{ route('reports.overview.pdf', ['month' => $selectedMonth, 'member_id' => $memberId]) }}"
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                {{ __('common.print_pdf') }}
            </a>
        </form>
    </div>
</div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">{{ __('common.total_bazar') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalBazar, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">{{ __('common.total_meal') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalMeal, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">{{ __('common.meal_rate') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($avgMealRate, 4) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">{{ __('common.total_deposits') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalDeposits, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">{{ __('common.total_house_rent') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalHouseRent, 2) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-500 uppercase tracking-wider">{{ __('common.net_balance') }} ({{ $netBalance >= 0 ? __('common.extra') : __('common.minus') }})</h3>
                        <p class="mt-1 text-3xl font-semibold {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($netBalance, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.sl') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.name') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.total_meal') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('house_rent.house_rent') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.deposit') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.total_charge') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('common.balance') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reportData as $index => $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ number_format($data['total_meal'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($data['house_rent'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($data['total_deposit'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-red-600">{{ number_format($data['total_charge'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $data['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($data['balance'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        {{ __('common.no_data_for_month') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('member_id');
    if (el) {
        new TomSelect(el, {
            placeholder: @json(__('common.search_or_select_member')),
            allowEmptyOption: true,
            persist: false,
            create: false,
        });
    }
});
</script>
</x-app-layout>