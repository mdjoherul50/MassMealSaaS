<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex">
            <!-- Left Side - Form -->
            <div class="flex-1 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 bg-white">
                <div class="mx-auto w-full max-w-md">
                    <div class="mb-8 text-center">
                        <a href="/" class="inline-block">
                            <x-site-logo class="w-auto h-14" />
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>

            <!-- Right Side - Branding -->
            <div class="hidden lg:flex flex-1 relative bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 overflow-hidden">
                <!-- Pattern Overlay -->
                <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                
                <!-- Decorative Circles -->
                <div class="absolute top-20 right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>

                <div class="relative z-10 p-12 text-white flex flex-col justify-between w-full">
                    <div class="flex justify-between items-center">
                        <a href="/" class="inline-flex items-center text-sm font-medium text-white/80 hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-left mr-2"></i>
                            {{ __('auth.back_to_home') }}
                        </a>
                    </div>
                    
                    <div class="max-w-md">
                        <h2 class="text-4xl font-bold leading-tight">
                            {{ __('auth.welcome') }}
                        </h2>
                        <p class="mt-6 text-lg text-white/80 leading-relaxed">
                            {{ __('auth.welcome_subtitle') }}
                        </p>
                        
                        <div class="mt-10 space-y-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white/20">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <span class="ml-4 text-white/90">{{ __('auth.no_credit_card') }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white/20">
                                    <i class="fa-solid fa-bolt text-white"></i>
                                </div>
                                <span class="ml-4 text-white/90">{{ __('auth.start_free_trial') }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white/20">
                                    <i class="fa-solid fa-users text-white"></i>
                                </div>
                                <span class="ml-4 text-white/90">{{ __('auth.join_thousands') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-sm text-white/60">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('landing.all_rights_reserved') }}.
                    </div>
                </div>
            </div>
        </div>

        <style>
            .bg-grid-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </body>
</html>
