<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.tenant_logo') }}
            </h2>
            <div class="flex gap-3">
                <x-language-switcher />
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div>
                        <div class="text-sm font-medium text-gray-700 mb-2">{{ __('common.current_logo') }}</div>
                        @if($tenantLogoPath)
                            <img src="{{ asset('storage/' . $tenantLogoPath) }}" class="h-16 w-auto" alt="{{ $tenant?->name ?? config('app.name') }}" />
                        @else
                            <x-application-logo class="h-16 w-auto fill-current text-gray-800" />
                        @endif
                    </div>

                    <form action="{{ route('tenant.branding.logo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('common.upload_new_logo') }}</label>
                            <input type="file" name="logo" accept="image/*" class="mt-1 block w-full" required />
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
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
