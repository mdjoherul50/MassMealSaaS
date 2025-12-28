<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('tenant.add_tenant') }}
            </h2>
            <div class="flex gap-3">
                <x-language-switcher />
                <a href="{{ route('superadmin.tenants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('superadmin.tenants.store') }}" method="POST">
                        @csrf

                        <!-- Tenant Information Section -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('tenant.tenant_details') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tenant Name -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        {{ __('tenant.tenant_name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">
                                        {{ __('common.phone') }}
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Plan -->
                                <div>
                                    <label for="plan_id" class="block text-sm font-medium text-gray-700">
                                        {{ __('plan.plan') }} <span class="text-red-500">*</span>
                                    </label>
                                    <select name="plan_id" id="plan_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">{{ __('plan.select_plan') }}</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - ৳{{ number_format((float) $plan->price_monthly, 0) }}/{{ __('plan.monthly') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('plan_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">
                                        {{ __('common.address') }}
                                    </label>
                                    <textarea name="address" id="address" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Owner Information Section -->
                        <div class="mb-8 border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('tenant.owner_information') }}</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Owner Name -->
                                <div>
                                    <label for="owner_name" class="block text-sm font-medium text-gray-700">
                                        {{ __('common.name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('owner_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Owner Email -->
                                <div>
                                    <label for="owner_email" class="block text-sm font-medium text-gray-700">
                                        {{ __('common.email') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('owner_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Owner Password -->
                                <div class="md:col-span-2">
                                    <label for="owner_password" class="block text-sm font-medium text-gray-700">
                                        {{ __('common.password') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="owner_password" id="owner_password" required minlength="8"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <p class="mt-1 text-sm text-gray-500">{{ __('common.minimum_8_characters') }}</p>
                                    @error('owner_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Information Box -->
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium">{{ __('common.note') }}:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        <li>{{ __('tenant.note_owner_account_will_be_created') }}</li>
                                        <li>{{ __('tenant.note_trial_period_based_on_plan') }}</li>
                                        <li>{{ __('tenant.note_owner_will_receive_credentials') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('superadmin.tenants.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.create') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
