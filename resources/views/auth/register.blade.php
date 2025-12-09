<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('auth.create_account') }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('auth.already_registered') }}
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">
                {{ __('auth.login_now') }}
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-user mr-1 text-gray-400"></i>
                {{ __('auth.name') }}
            </label>
            <x-text-input id="name" class="block w-full pl-4 pr-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" :placeholder="__('auth.name')" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-envelope mr-1 text-gray-400"></i>
                {{ __('auth.email') }}
            </label>
            <x-text-input id="email" class="block w-full pl-4 pr-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" :placeholder="__('auth.email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mess Name -->
        <div class="mt-4">
            <label for="mess_name" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-building mr-1 text-gray-400"></i>
                {{ __('auth.mess_name') }}
            </label>
            <x-text-input id="mess_name" class="block w-full pl-4 pr-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" type="text" name="mess_name" :value="old('mess_name')" required autocomplete="organization" :placeholder="__('auth.mess_name')" />
            <x-input-error :messages="$errors->get('mess_name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-lock mr-1 text-gray-400"></i>
                {{ __('auth.password') }}
            </label>
            <div class="relative">
                <x-text-input id="password" class="block w-full pl-4 pr-12 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                                type="password"
                                name="password"
                                required autocomplete="new-password" :placeholder="__('auth.password')" />
                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-eye" id="password-icon"></i>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ __('auth.password_requirements') }}</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-lock mr-1 text-gray-400"></i>
                {{ __('auth.confirm_password') }}
            </label>
            <div class="relative">
                <x-text-input id="password_confirmation" class="block w-full pl-4 pr-12 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" :placeholder="__('auth.confirm_password')" />
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-eye" id="password_confirmation-icon"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transition-all">
                <i class="fa-solid fa-user-plus mr-2"></i>
                {{ __('auth.register') }}
            </button>
        </div>

        <!-- Info -->
        <div class="mt-6 p-4 bg-indigo-50 rounded-xl">
            <div class="flex">
                <i class="fa-solid fa-info-circle text-indigo-500 mt-0.5 mr-3"></i>
                <div class="text-sm text-indigo-700">
                    <p class="font-medium">{{ __('auth.start_free_trial') }}</p>
                    <p class="mt-1 text-indigo-600">{{ __('auth.no_credit_card') }}</p>
                </div>
            </div>
        </div>
    </form>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>
