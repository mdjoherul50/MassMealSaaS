<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.bazar_entry_details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-2"><strong>{{ __('common.date') }}:</strong> {{ \Carbon\Carbon::parse($bazar->date)->format('d M, Y') }}</p>
                    <p class="mb-2"><strong>{{ __('common.buyer') }}:</strong> {{ $bazar->buyer->name ?? __('common.na') }}</p>
                    <p class="mb-2"><strong>{{ __('common.amount') }}:</strong> {{ number_format($bazar->total_amount, 2) }}</p>
                    <p class="mb-2"><strong>{{ __('common.description') }}:</strong> {{ $bazar->description ?? __('common.na') }}</p>
                    <hr class="my-4">
                    <p class="text-sm text-gray-500">{{ __('common.entry_created_at') }}: {{ $bazar->created_at->format('d M, Y h:i A') }}</p>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('bazars.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('common.back_to_list') }}
                        </a>
                        @can('bazars.manage')
                        <a href="{{ route('bazars.edit', $bazar) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 ms-4">
                            {{ __('common.edit') }}
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>