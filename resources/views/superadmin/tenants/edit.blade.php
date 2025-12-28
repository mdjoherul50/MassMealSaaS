<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('tenant.edit_tenant') }}
            </h2>
            <div class="flex gap-3">
                <x-language-switcher />
                <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('superadmin.tenants.update', $tenant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    {{ __('tenant.tenant_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    {{ __('common.phone') }}
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="plan_id" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.plan') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="plan_id" id="plan_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('plan.select_plan') }}</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('plan_id', $tenant->plan_id) == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} - ৳{{ number_format((float) $plan->price_monthly, 0) }}/{{ __('plan.monthly') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">
                                    {{ __('common.address') }}
                                </label>
                                <textarea name="address" id="address" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $tenant->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    {{ __('common.status') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                    <option value="suspended" {{ old('status', $tenant->status) == 'suspended' ? 'selected' : '' }}>{{ __('common.suspended') }}</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subscription_status" class="block text-sm font-medium text-gray-700">
                                    {{ __('common.subscription_status') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="subscription_status" id="subscription_status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="trial" {{ old('subscription_status', $tenant->subscription_status) == 'trial' ? 'selected' : '' }}>{{ __('common.trial') }}</option>
                                    <option value="active" {{ old('subscription_status', $tenant->subscription_status) == 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                                    <option value="expired" {{ old('subscription_status', $tenant->subscription_status) == 'expired' ? 'selected' : '' }}>{{ __('common.expired') }}</option>
                                    <option value="cancelled" {{ old('subscription_status', $tenant->subscription_status) == 'cancelled' ? 'selected' : '' }}>{{ __('common.cancelled') }}</option>
                                </select>
                                @error('subscription_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="plan_expires_at" class="block text-sm font-medium text-gray-700">
                                    {{ __('tenant.plan_expires_at') }}
                                </label>
                                <input type="date" name="plan_expires_at" id="plan_expires_at" value="{{ old('plan_expires_at', optional($tenant->plan_expires_at)->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('plan_expires_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('common.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
