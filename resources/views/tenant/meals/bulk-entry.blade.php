<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Meal Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="GET" action="{{ route('meals.bulkEntry') }}" class="mb-4 flex items-center">
                        <x-input-label for="date" :value="__('Select Date')" class="mr-2" />
                        <x-text-input id="date" type="date" name="date" :value="$selectedDate" required />
                        <x-primary-button class="ms-2">
                            {{ __('Load') }}
                        </x-primary-button>
                    </form>

                    <form action="{{ route('meals.bulkStore') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $selectedDate }}">

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member Name</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Breakfast</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Lunch</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Dinner</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($members as $member)
                                    @php
                                        // ওই সদস্যের জন্য সেভ করা ডেটা (যদি থাকে)
                                        $meal = $mealsData->get($member->id, ['breakfast' => 0, 'lunch' => 0, 'dinner' => 0]);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                                        <td class="px-6 py-4">
                                            <x-text-input type="number" name="meals[{{ $member->id }}][breakfast]" 
                                                          class="w-20 text-center" 
                                                          :value="$meal['breakfast']" 
                                                          min="0" max="10" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-text-input type="number" name="meals[{{ $member->id }}][lunch]" 
                                                          class="w-20 text-center" 
                                                          :value="$meal['lunch']" 
                                                          min="0" max="10" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-text-input type="number" name="meals[{{ $member->id }}][dinner]" 
                                                          class="w-20 text-center" 
                                                          :value="$meal['dinner']" 
                                                          min="0" max="10" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No members found. Please <a href="{{ route('members.create') }}" class="text-indigo-600 underline">add members</a> first.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @if($members->count() > 0)
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Save Meals') }}
                                </x-primary-button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>