<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add House Rent Month') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('house-rent-mains.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="month" :value="__('Month')" />
                                <x-text-input id="month" type="month" name="month" required />
                                <x-input-error :messages="$errors->get('month')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="house_rent" :value="__('House Rent')" />
                                <x-text-input id="house_rent" type="number" step="0.01" name="house_rent" required />
                                <x-input-error :messages="$errors->get('house_rent')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="wifi_bill" :value="__('Wifi Bill')" />
                                <x-text-input id="wifi_bill" type="number" step="0.01" name="wifi_bill" />
                                <x-input-error :messages="$errors->get('wifi_bill')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="current_bill" :value="__('Current Bill')" />
                                <x-text-input id="current_bill" type="number" step="0.01" name="current_bill" />
                                <x-input-error :messages="$errors->get('current_bill')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="gas_bill" :value="__('Gas Bill')" />
                                <x-text-input id="gas_bill" type="number" step="0.01" name="gas_bill" />
                                <x-input-error :messages="$errors->get('gas_bill')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="extra_bill" :value="__('Extra Bill')" />
                                <x-text-input id="extra_bill" type="number" step="0.01" name="extra_bill" />
                                <x-input-error :messages="$errors->get('extra_bill')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="extra_note" :value="__('Extra Note')" />
                                <x-text-input id="extra_note" type="text" name="extra_note" />
                                <x-input-error :messages="$errors->get('extra_note')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pending">Pending</option>
                                    <option value="partial">Partial</option>
                                    <option value="paid">Paid</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('house-rent-mains.index') }}" class="mr-4 text-gray-600 hover:underline">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button type="submit">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
