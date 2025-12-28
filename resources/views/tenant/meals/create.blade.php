<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tenant.add_single_meal_entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('meals.store') }}" method="POST">
                        @csrf
                        
                        <div class="mt-4">
                            <x-input-label for="member_id" :value="__('tenant.member')" />
                            <select name="member_id" id="member_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">{{ __('common.select_member') }}</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="date" :value="__('common.date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div>
                                <x-input-label for="breakfast" :value="__('common.breakfast')" />
                                <x-text-input id="breakfast" class="block mt-1 w-full" type="number" name="breakfast" :value="old('breakfast', 0)" required />
                                <x-input-error :messages="$errors->get('breakfast')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="lunch" :value="__('common.lunch')" />
                                <x-text-input id="lunch" class="block mt-1 w-full" type="number" name="lunch" :value="old('lunch', 0)" required />
                                <x-input-error :messages="$errors->get('lunch')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="dinner" :value="__('common.dinner')" />
                                <x-text-input id="dinner" class="block mt-1 w-full" type="number" name="dinner" :value="old('dinner', 0)" required />
                                <x-input-error :messages="$errors->get('dinner')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('meals.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('common.cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('common.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>