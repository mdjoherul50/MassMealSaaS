<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.edit_deposit_entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('deposits.update', $deposit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="date" :value="__('common.date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $deposit->date)" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="member_id" :value="__('tenant.member')" />
                            <select name="member_id" id="member_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('common.select_member') }}</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id', $deposit->member_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('common.amount')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $deposit->amount)" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="method" :value="__('common.method_optional')" />
                            <x-text-input id="method" class="block mt-1 w-full" type="text" name="method" :value="old('method', $deposit->method)" />
                            <x-input-error :messages="$errors->get('method')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="reference" :value="__('common.reference_optional')" />
                            <x-text-input id="reference" class="block mt-1 w-full" type="text" name="reference" :value="old('reference', $deposit->reference)" />
                            <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('deposits.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md">
                                {{ __('common.cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('common.update') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
