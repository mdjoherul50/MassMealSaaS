<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tenant.view_meal_entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-2"><strong>{{ __('tenant.member') }}:</strong> {{ $meal->member->name ?? __('common.na') }}</p>
                    <p class="mb-2"><strong>{{ __('common.date') }}:</strong> {{ \Carbon\Carbon::parse($meal->date)->format('d M, Y') }}</p>
                    <hr class="my-4">
                    <p class="mb-2"><strong>{{ __('common.breakfast') }}:</strong> {{ $meal->breakfast }}</p>
                    <p class="mb-2"><strong>{{ __('common.lunch') }}:</strong> {{ $meal->lunch }}</p>
                    <p class="mb-2"><strong>{{ __('common.dinner') }}:</strong> {{ $meal->dinner }}</p>
                    <hr class="my-4">
                    <p class="text-sm text-gray-500">{{ __('common.last_updated') }}: {{ $meal->updated_at->format('d M, Y h:i A') }}</p>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('meals.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('common.back_to_list') }}
                        </a>
                        @can('meals.manage')
                        <a href="{{ route('meals.edit', $meal) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 ms-4">
                            {{ __('common.edit') }}
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>