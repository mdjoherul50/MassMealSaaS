<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Link our custom auth CSS -->
        @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex bg-gray-50 lg:bg-white">
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div class="mb-8">
                        <a href="/">
                            <x-application-logo class="w-auto h-12 fill-current text-gray-800" />
                        </a>
                    </div>

                    @if (isset($title))
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ $title }}
                            </h2>
                            @if (isset($subtitle))
                                <p class="mt-2 text-sm text-gray-600">
                                    {{ $subtitle }}
                                </p>
                            @endif
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>

            <div class="hidden lg:flex flex-1 auth-sidebar-bg">
                <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1974&auto=format&fit=crop"
                     alt="A person preparing a meal"
                     class="w-full h-full object-cover">
            </div>
        </div>

        <script>
            function setupPasswordToggle(inputId) {
                const passwordInput = document.getElementById(inputId);
                const toggleButton = document.getElementById(inputId + '-toggle');
                const eyeIcon = toggleButton.querySelector('.eye-icon');
                const eyeOffIcon = toggleButton.querySelector('.eye-off-icon');

                toggleButton.addEventListener('click', function () {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.style.display = 'none';
                        eyeOffIcon.style.display = 'block';
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.style.display = 'block';
                        eyeOffIcon.style.display = 'none';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (document.getElementById('password')) {
                    setupPasswordToggle('password');
                }
                if (document.getElementById('password_confirmation')) {
                    setupPasswordToggle('password_confirmation');
                }
            });
        </script>

    </body>
</html>
