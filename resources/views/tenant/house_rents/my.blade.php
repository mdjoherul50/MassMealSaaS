<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My House Rent') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Your House Rent Entries</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Total amounts are calculated from the main rent record for each month. Any remaining balance is carried forward to the next month.
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">House Rent</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Wifi</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Current</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Extra</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($rents as $rent)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $rent->month }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($rent->house_rent, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($rent->wifi_bill, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($rent->current_bill, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($rent->gas_bill, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        {{ number_format($rent->extra_bill, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-semibold">
                                        {{ number_format($rent->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($rent->status === 'paid') bg-green-100 text-green-800
                                            @elseif($rent->status === 'partial') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($rent->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $rent->extra_note ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No house rent entries found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $rents->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
