<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex">
            <div class="flex-1 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div class="mb-8">
                        <a href="/">
                            <x-application-logo class="w-auto h-12 fill-current text-gray-800" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>

            {{-- নতুন ইমেজ ব্যাকগ্রাউন্ড সেকশন --}}
            <div class="hidden lg:flex flex-1 relative bg-cover bg-center"
                 style="background-image: url('{{ asset('images/auth-bg.jpg') }}');">
                {{-- Overlay for better text readability --}}
                <div class="absolute inset-0 bg-gray-900 opacity-70"></div>

                <div class="relative z-10 p-12 text-white flex flex-col justify-between">
                    <div class="text-right">
                        <a href="/" class="text-sm font-medium text-gray-300 hover:text-white">&larr; Back to Home</a>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold">
                            Welcome to MassMeal
                        </h2>
                        <p class="mt-4 text-lg text-gray-300">
                            The all-in-one solution for managing your mess. Track meals, calculate costs, and manage deposits with ease.
                        </p>
                    </div>
                    <div class="text-sm text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name') }}.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
