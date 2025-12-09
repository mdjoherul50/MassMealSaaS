<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('plan.add_plan') }}
            </h2>
            <div class="flex gap-3">
                <x-language-switcher />
                <a href="{{ route('superadmin.plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('superadmin.plans.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Plan Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.plan_name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">
                                    Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Monthly Price -->
                            <div>
                                <label for="price_monthly" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.price_per_month') }} (৳) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price_monthly" id="price_monthly" value="{{ old('price_monthly', 0) }}" step="0.01" min="0" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('price_monthly')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Yearly Price -->
                            <div>
                                <label for="price_yearly" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.price_per_year') }} (৳) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price_yearly" id="price_yearly" value="{{ old('price_yearly', 0) }}" step="0.01" min="0" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('price_yearly')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Members -->
                            <div>
                                <label for="max_members" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.max_members') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="max_members" id="max_members" value="{{ old('max_members', 10) }}" min="1" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('max_members')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Storage -->
                            <div>
                                <label for="max_storage_mb" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.max_storage') }} (MB) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="max_storage_mb" id="max_storage_mb" value="{{ old('max_storage_mb', 1000) }}" min="1" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('max_storage_mb')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Trial Days -->
                            <div>
                                <label for="trial_days" class="block text-sm font-medium text-gray-700">
                                    {{ __('plan.trial_days') }}
                                </label>
                                <input type="number" name="trial_days" id="trial_days" value="{{ old('trial_days', 0) }}" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('trial_days')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700">
                                    Sort Order
                                </label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('sort_order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                {{ __('plan.plan_description') }}
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Features -->
                        <div class="mt-6">
                            <label for="features" class="block text-sm font-medium text-gray-700">
                                {{ __('plan.features') }} (one per line)
                            </label>
                            <textarea name="features_text" id="features_text" rows="5" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('features_text') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Enter each feature on a new line</p>
                        </div>

                        <!-- Checkboxes -->
                        <div class="mt-6 space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    {{ __('plan.is_active') }}
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_popular" class="ml-2 block text-sm text-gray-900">
                                    {{ __('plan.is_popular') }}
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('superadmin.plans.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function() {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('slug').value = slug;
        });

        // Convert features textarea to array before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const featuresText = document.getElementById('features_text').value;
            if (featuresText.trim()) {
                const features = featuresText.split('\n').filter(f => f.trim());
                
                // Create hidden input for features array
                features.forEach((feature, index) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `features[${index}]`;
                    input.value = feature.trim();
                    this.appendChild(input);
                });
            }
        });
    </script>
</x-app-layout>
