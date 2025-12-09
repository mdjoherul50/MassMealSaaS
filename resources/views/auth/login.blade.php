<x-guest-layout>
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('auth.sign_in') }}</h1>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('auth.dont_have_account') }}
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">
                {{ __('auth.register_now') }}
            </a>
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-envelope mr-1 text-gray-400"></i>
                {{ __('auth.email') }}
            </label>
            <div class="relative">
                <x-text-input id="email" class="block w-full pl-4 pr-4 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" :placeholder="__('auth.email')" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                <i class="fa-solid fa-lock mr-1 text-gray-400"></i>
                {{ __('auth.password') }}
            </label>
            <div class="relative">
                <x-text-input id="password" class="block w-full pl-4 pr-12 py-3 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                :placeholder="__('auth.password')" />
                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-eye" id="password-icon"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full flex justify-center items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transition-all">
                <i class="fa-solid fa-right-to-bracket mr-2"></i>
                {{ __('auth.login') }}
            </button>
        </div>

        <!-- Divider -->
        <div class="mt-6 relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-500">{{ __('auth.secure_login') }}</span>
            </div>
        </div>

        <p class="mt-4 text-center text-xs text-gray-500">
            <i class="fa-solid fa-shield-check text-green-500 mr-1"></i>
            {{ __('Your data is protected with 256-bit SSL encryption') ?? 'Your data is protected with 256-bit SSL encryption' }}
        </p>
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
