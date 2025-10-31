<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Meal Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-2"><strong>Member:</strong> {{ $meal->member->name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Date:</strong> {{ \Carbon\Carbon::parse($meal->date)->format('d M, Y') }}</p>
                    <hr class="my-4">
                    <p class="mb-2"><strong>Breakfast:</strong> {{ $meal->breakfast }}</p>
                    <p class="mb-2"><strong>Lunch:</strong> {{ $meal->lunch }}</p>
                    <p class="mb-2"><strong>Dinner:</strong> {{ $meal->dinner }}</p>
                    <hr class="my-4">
                    <p class="text-sm text-gray-500">Last updated: {{ $meal->updated_at->format('d M, Y h:i A') }}</p>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('meals.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Back to List') }}
                        </a>
                        @can('meals.manage')
                        <a href="{{ route('meals.edit', $meal) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 ms-4">
                            {{ __('Edit') }}
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>