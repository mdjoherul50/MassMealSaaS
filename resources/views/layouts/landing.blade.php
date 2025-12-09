<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Mess Management Made Easy</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col bg-white">

            <header x-data="{ mobileMenuOpen: false }" class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="/">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center md:hidden">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none">
                                <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Desktop menu -->
                        <div class="hidden md:flex items-center space-x-6">
                            <a href="#features" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">{{ __('landing.features') }}</a>
                            <a href="#pricing" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">{{ __('landing.pricing') }}</a>

                            <!-- Language Switcher Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
                                    <i class="fa-solid fa-globe mr-1"></i>
                                    বাংলা / English
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm {{ app()->getLocale() == 'en' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                            <i class="fa-solid fa-check mr-2 {{ app()->getLocale() == 'en' ? '' : 'invisible' }}"></i>
                                            English
                                        </button>
                                    </form>
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="bn">
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm {{ app()->getLocale() == 'bn' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                                            <i class="fa-solid fa-check mr-2 {{ app()->getLocale() == 'bn' ? '' : 'invisible' }}"></i>
                                            বাংলা
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 transition-colors shadow-sm">
                                        <i class="fa-solid fa-gauge-high mr-2"></i>
                                        {{ __('common.dashboard') }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition-colors">
                                        {{ __('auth.login') }}
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 transition-colors shadow-sm">
                                            {{ __('auth.register') }}
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>

                    <!-- Mobile menu -->
                    <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200">
                        <div class="py-4 space-y-3">
                            <a href="#features" @click="mobileMenuOpen = false" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                                <i class="fa-solid fa-sparkles mr-2"></i>
                                {{ __('landing.features') }}
                            </a>
                            <a href="#pricing" @click="mobileMenuOpen = false" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg">
                                <i class="fa-solid fa-tags mr-2"></i>
                                {{ __('landing.pricing') }}
                            </a>
                            
                            <!-- Mobile Language Switcher -->
                            <div class="px-4 py-2">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-2">{{ __('common.language') }}</p>
                                <div class="flex space-x-2">
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                        <button type="submit" class="px-4 py-2 text-sm rounded-lg {{ app()->getLocale() == 'en' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                                            English
                                        </button>
                                    </form>
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="bn">
                                        <button type="submit" class="px-4 py-2 text-sm rounded-lg {{ app()->getLocale() == 'bn' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                                            বাংলা
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="px-4 pt-4 border-t border-gray-200 space-y-2">
                                @if (Route::has('login'))
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">
                                            <i class="fa-solid fa-gauge-high mr-2"></i>
                                            {{ __('common.dashboard') }}
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                                            {{ __('auth.login') }}
                                        </a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">
                                                {{ __('auth.register') }}
                                            </a>
                                        @endif
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="bg-gray-900 text-white">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Brand -->
                        <div class="col-span-1 md:col-span-2">
                            <x-application-logo class="h-10 w-auto fill-current text-white" />
                            <p class="mt-4 text-gray-400 text-sm max-w-md">
                                {{ __('landing.hero_subtitle') }}
                            </p>
                        </div>
                        
                        <!-- Quick Links -->
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300">{{ __('landing.features') }}</h4>
                            <ul class="mt-4 space-y-2">
                                <li><a href="#features" class="text-gray-400 hover:text-white text-sm">{{ __('landing.feature_member_title') }}</a></li>
                                <li><a href="#features" class="text-gray-400 hover:text-white text-sm">{{ __('landing.feature_meal_title') }}</a></li>
                                <li><a href="#features" class="text-gray-400 hover:text-white text-sm">{{ __('landing.feature_report_title') }}</a></li>
                                <li><a href="#features" class="text-gray-400 hover:text-white text-sm">{{ __('landing.feature_rent_title') }}</a></li>
                            </ul>
                        </div>
                        
                        <!-- Contact -->
                        <div>
                            <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300">{{ __('landing.contact') }}</h4>
                            <ul class="mt-4 space-y-2">
                                <li class="flex items-center text-gray-400 text-sm">
                                    <i class="fa-solid fa-envelope mr-2"></i>
                                    support@massmeal.com
                                </li>
                                <li class="flex items-center text-gray-400 text-sm">
                                    <i class="fa-brands fa-github mr-2"></i>
                                    <a href="https://github.com/mdjoherul50" class="hover:text-white" target="_blank">GitHub</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-400 text-sm">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('landing.all_rights_reserved') }}.
                        </p>
                        <p class="text-gray-400 text-sm mt-2 md:mt-0">
                            {{ __('landing.developed_by') }} <a href="https://github.com/mdjoherul50" class="text-indigo-400 hover:text-indigo-300" target="_blank">Jahirul Islam</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
