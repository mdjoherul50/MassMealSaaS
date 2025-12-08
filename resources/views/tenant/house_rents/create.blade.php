<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New House Rent') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('house-rents.store') }}" method="POST">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="month" :value="__('Month')" />
                            <x-text-input id="month" class="block mt-1 w-full" type="month" name="month" :value="old('month', date('Y-m'))" required />
                            <x-input-error :messages="$errors->get('month')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="member_id" :value="__('Member')" />
                            <select name="member_id" id="member_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Member') }}</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="house_rent" :value="__('House Rent')" />
                                <x-text-input id="house_rent" class="block mt-1 w-full" type="number" step="0.01" name="house_rent" :value="old('house_rent')" required />
                                <x-input-error :messages="$errors->get('house_rent')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="wifi_bill" :value="__('Wifi Bill')" />
                                <x-text-input id="wifi_bill" class="block mt-1 w-full" type="number" step="0.01" name="wifi_bill" :value="old('wifi_bill', 0)" />
                                <x-input-error :messages="$errors->get('wifi_bill')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="current_bill" :value="__('Current Bill')" />
                                <x-text-input id="current_bill" class="block mt-1 w-full" type="number" step="0.01" name="current_bill" :value="old('current_bill', 0)" />
                                <x-input-error :messages="$errors->get('current_bill')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="gas_bill" :value="__('Gas Bill')" />
                                <x-text-input id="gas_bill" class="block mt-1 w-full" type="number" step="0.01" name="gas_bill" :value="old('gas_bill', 0)" />
                                <x-input-error :messages="$errors->get('gas_bill')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="extra_bill" :value="__('Extra Bill')" />
                                <x-text-input id="extra_bill" class="block mt-1 w-full" type="number" step="0.01" name="extra_bill" :value="old('extra_bill', 0)" />
                                <x-input-error :messages="$errors->get('extra_bill')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="extra_note" :value="__('Extra Note (Optional)')" />
                            <x-text-input id="extra_note" class="block mt-1 w-full" type="text" name="extra_note" :value="old('extra_note')" />
                            <x-input-error :messages="$errors->get('extra_note')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('house-rents.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Save House Rent') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
