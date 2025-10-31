<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bazar Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('bazars.update', $bazar) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mt-4">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date', $bazar->date)" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="buyer_id" :value="__('Buyer')" />
                            <select name="buyer_id" id="buyer_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Buyer</option>
                                @foreach ($buyers as $buyer)
                                    <option value="{{ $buyer->id }}" {{ old('buyer_id', $bazar->buyer_id) == $buyer->id ? 'selected' : '' }}>
                                        {{ $buyer->name }} ({{ $buyer->role->name }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('buyer_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="total_amount" :value="__('Total Amount')" />
                            <x-text-input id="total_amount" class="block mt-1 w-full" type="number" step="0.01" name="total_amount" :value="old('total_amount', $bazar->total_amount)" required />
                            <x-input-error :messages="$errors->get('total_amount')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description (Optional)')" />
                            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description', $bazar->description)" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('bazars.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Update Entry') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>