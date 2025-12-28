<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.all_meal_entries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-end mb-4">
                        @can('meals.manage')
                        <a href="{{ route('meals.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('tenant.add_single_meal_entry') }}
                        </a>
                        @endcan
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('common.date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('tenant.member') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.breakfast') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.lunch') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('common.dinner') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($meals as $meal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($meal->date)->format('d M, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $meal->member->name ?? __('common.na') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $meal->breakfast }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $meal->lunch }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $meal->dinner }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('meals.show', $meal) }}" class="text-gray-600 hover:text-gray-900">{{ __('common.view') }}</a>
                                            @can('meals.manage')
                                            <a href="{{ route('meals.edit', $meal) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">{{ __('common.edit') }}</a>
                                            <form action="{{ route('meals.destroy', $meal) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('common.delete_confirmation') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('common.delete') }}</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            {{ __('common.no_meal_entries_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $meals->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>