<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('house_rent.house_rent_management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">{{ __('house_rent.monthly_house_rent_summary') }}</h3>
                        <a href="{{ route('house-rent-mains.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <i class="fa-solid fa-plus mr-2"></i>
                            {{ __('house_rent.add_month') }}
                        </a>
                    </div>

                    <div class="mb-6">
                        <form method="GET" action="{{ route('house-rent-mains.index') }}" class="flex items-end gap-3">
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700">{{ __('house_rent.select_month') }}</label>
                                <select id="month" name="month" class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()">
                                    @foreach ($months ?? [] as $m)
                                        <option value="{{ $m }}" @selected(($selectedMonth ?? null) === $m)>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2 pb-1">
                                <input id="show_inactive" name="show_inactive" type="checkbox" value="1" class="rounded border-gray-300" onchange="this.form.submit()" @checked(($showInactive ?? false) === true)>
                                <label for="show_inactive" class="text-sm text-gray-700">{{ __('house_rent.show_inactive_members') }}</label>
                            </div>
                            <noscript>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md">{{ __('common.submit') }}</button>
                            </noscript>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.month') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.house_rent') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.wifi_bill') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.current_bill') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.gas_bill') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.extra_bill') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.total') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.assigned_to_members') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.remaining_balance') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($rents as $rent)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $rent->month }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($rent->house_rent, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($rent->wifi_bill, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($rent->current_bill, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($rent->gas_bill, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($rent->extra_bill, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold">{{ number_format($rent->total, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($rent->assigned_to_members, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $rent->remaining_balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($rent->remaining_balance, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($rent->status === 'paid') bg-green-100 text-green-800
                                                @elseif($rent->status === 'partial') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($rent->status === 'paid')
                                                    {{ __('house_rent.paid') }}
                                                @elseif($rent->status === 'partial')
                                                    {{ __('house_rent.partial') }}
                                                @else
                                                    {{ __('house_rent.pending') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('house-rent-mains.edit', $rent) }}" class="text-blue-600 hover:underline">{{ __('common.edit') }}</a>
                                            <form method="POST" action="{{ route('house-rent-mains.sync', $rent->month) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="ml-2 text-green-600 hover:underline">{{ __('house_rent.sync_assigned') }}</button>
                                            </form>
                                            @if($rent->remaining_balance > 0)
                                            <form method="POST" action="{{ route('house-rent-mains.carry-forward', $rent->month) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="ml-2 text-purple-600 hover:underline">{{ __('house_rent.carry_forward') }}</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            {{ __('common.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $rents->links() }}
                    </div>

                    @if (!empty($selectedMonth) && $main)
                        <div class="mt-10 border-t pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium">{{ __('house_rent.member_wise_rent') }} ({{ $selectedMonth }})</h3>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('house-rent-mains.copy-previous', $selectedMonth) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900" onclick="return confirm('{{ __('house_rent.copy_previous_confirm', ['month' => $selectedMonth]) }}');">{{ __('house_rent.copy_previous_month') }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('house-rent-mains.sync', $selectedMonth) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">{{ __('house_rent.sync_assigned') }}</button>
                                    </form>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('house-rent-mains.members', $selectedMonth) }}">
                                @csrf

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('tenant.members') }}</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.house_rent') }}</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.wifi_bill') }}</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.current_bill') }}</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.gas_bill') }}</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.extra_bill') }}</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.extra_note') }}</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('house_rent.paid_amount') }}</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($members as $i => $member)
                                                @php
                                                    $rent = $memberRents[$member->id] ?? null;
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap">
                                                        <div class="font-medium">{{ $member->name }}</div>
                                                        <input type="hidden" name="rents[{{ $i }}][member_id]" value="{{ $member->id }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" step="0.01" min="0" class="w-28 rounded-md border-gray-300" name="rents[{{ $i }}][house_rent]" value="{{ old('rents.' . $i . '.house_rent', $rent->house_rent ?? 0) }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" step="0.01" min="0" class="w-24 rounded-md border-gray-300" name="rents[{{ $i }}][wifi_bill]" value="{{ old('rents.' . $i . '.wifi_bill', $rent->wifi_bill ?? 0) }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" step="0.01" min="0" class="w-24 rounded-md border-gray-300" name="rents[{{ $i }}][current_bill]" value="{{ old('rents.' . $i . '.current_bill', $rent->current_bill ?? 0) }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" step="0.01" min="0" class="w-24 rounded-md border-gray-300" name="rents[{{ $i }}][gas_bill]" value="{{ old('rents.' . $i . '.gas_bill', $rent->gas_bill ?? 0) }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" step="0.01" min="0" class="w-24 rounded-md border-gray-300" name="rents[{{ $i }}][extra_bill]" value="{{ old('rents.' . $i . '.extra_bill', $rent->extra_bill ?? 0) }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="text" class="w-56 rounded-md border-gray-300" name="rents[{{ $i }}][extra_note]" value="{{ old('rents.' . $i . '.extra_note', $rent->extra_note ?? '') }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="number" step="0.01" min="0" class="w-24 rounded-md border-gray-300" name="rents[{{ $i }}][paid_amount]" value="{{ old('rents.' . $i . '.paid_amount', $rent->paid_amount ?? 0) }}">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <select class="w-28 rounded-md border-gray-300" name="rents[{{ $i }}][status]">
                                                            @php
                                                                $statusVal = old('rents.' . $i . '.status', $rent->status ?? 'pending');
                                                            @endphp
                                                            <option value="pending" @selected($statusVal === 'pending')>{{ __('house_rent.pending') }}</option>
                                                            <option value="partial" @selected($statusVal === 'partial')>{{ __('house_rent.partial') }}</option>
                                                            <option value="paid" @selected($statusVal === 'paid')>{{ __('house_rent.paid') }}</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">{{ __('house_rent.save_member_rents') }}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
