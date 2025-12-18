<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.deposit_list') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('deposits.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('common.add_new_deposit') }}
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                       <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('tenant.member') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.amount') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.method') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($deposits as $deposit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($deposit->date)->format('d M, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $deposit->member->name ?? __('common.na') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($deposit->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $deposit->method }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('deposits.show', $deposit) }}" class="text-gray-600 hover:text-gray-900">{{ __('common.view') }}</a>
                                        @can('deposits.manage')
                                        <a href="{{ route('deposits.edit', $deposit) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">{{ __('common.edit') }}</a>
                                        <form action="{{ route('deposits.destroy', $deposit) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('common.are_you_sure') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">{{ __('common.delete') }}</button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        {{ __('common.no_deposit_entries_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $deposits->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
