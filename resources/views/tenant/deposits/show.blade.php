<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.deposit_details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-2"><strong>{{ __('common.date') }}:</strong> {{ \Carbon\Carbon::parse($deposit->date)->format('d M, Y') }}</p>
                    <p class="mb-2"><strong>{{ __('tenant.member') }}:</strong> {{ $deposit->member->name ?? __('common.na') }}</p>
                    <p class="mb-2"><strong>{{ __('common.amount') }}:</strong> {{ number_format($deposit->amount, 2) }}</p>
                    <p class="mb-2"><strong>{{ __('common.method') }}:</strong> {{ $deposit->method ?? __('common.na') }}</p>
                    <p class="mb-2"><strong>{{ __('common.reference') }}:</strong> {{ $deposit->reference ?? __('common.na') }}</p>
                    <hr class="my-4">
                    <p class="text-sm text-gray-500">{{ __('common.entry_created_at') }}: {{ $deposit->created_at->format('d M, Y h:i A') }}</p>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('deposits.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('common.back_to_list') }}
                        </a>
                        @can('deposits.manage')
                        <a href="{{ route('deposits.edit', $deposit) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 ms-4">
                            {{ __('common.edit') }}
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
