<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - {{ __('landing.tagline') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col">

            <!-- Modern Glassmorphism Header -->
            <header x-data="{ mobileMenuOpen: false, scrolled: false }" 
                    @scroll.window="scrolled = window.pageYOffset > 20"
                    :class="scrolled ? 'bg-gray-900/95 backdrop-blur-xl shadow-2xl' : 'bg-transparent'"
                    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-20">
                        <div class="flex items-center">
                            <a href="/" class="flex items-center space-x-2">
                                <x-site-logo class="block h-10 w-auto" />
                            </a>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center md:hidden">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2.5 rounded-xl text-white hover:bg-white/10 focus:outline-none transition-colors">
                                <svg class="h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <svg class="h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Desktop menu -->
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="#features" class="text-sm font-medium text-gray-200 hover:text-white transition-colors">{{ __('landing.features') }}</a>
                            <a href="#pricing" class="text-sm font-medium text-gray-200 hover:text-white transition-colors">{{ __('landing.pricing') }}</a>

                            <!-- Language Switcher Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-200 hover:text-white transition-colors px-3 py-2 rounded-xl hover:bg-white/10">
                                    <i class="fa-solid fa-globe mr-2"></i>
                                    {{ app()->getLocale() == 'bn' ? 'বাংলা' : 'EN' }}
                                    <svg class="ml-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-44 bg-gray-900/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 py-2 z-50">
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm flex items-center {{ app()->getLocale() == 'en' ? 'text-purple-400 bg-purple-500/10' : 'text-gray-300 hover:bg-white/5' }} transition-colors">
                                            <i class="fa-solid fa-check mr-3 {{ app()->getLocale() == 'en' ? 'text-purple-400' : 'invisible' }}"></i>
                                            {{ __('common.english') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="bn">
                                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm flex items-center {{ app()->getLocale() == 'bn' ? 'text-purple-400 bg-purple-500/10' : 'text-gray-300 hover:bg-white/5' }} transition-colors">
                                            <i class="fa-solid fa-check mr-3 {{ app()->getLocale() == 'bn' ? 'text-purple-400' : 'invisible' }}"></i>
                                            {{ __('common.bangla') }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl font-semibold text-sm text-white hover:from-purple-500 hover:to-indigo-500 transition-all shadow-lg shadow-purple-500/25 hover:scale-105">
                                        <i class="fa-solid fa-gauge-high mr-2"></i>
                                        {{ __('common.dashboard') }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-200 hover:text-white transition-colors px-4 py-2">
                                        {{ __('auth.login') }}
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl font-semibold text-sm text-white hover:from-purple-500 hover:to-indigo-500 transition-all shadow-lg shadow-purple-500/25 hover:scale-105">
                                            {{ __('auth.register') }}
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>

                    <!-- Mobile menu -->
                    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden absolute left-4 right-4 top-full mt-2 bg-gray-900/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/10 overflow-hidden">
                        <div class="py-4 space-y-2">
                            <a href="#features" @click="mobileMenuOpen = false" class="block px-5 py-3 text-base font-medium text-gray-200 hover:text-white hover:bg-white/5 transition-colors">
                                <i class="fa-solid fa-sparkles mr-3 text-purple-400"></i>
                                {{ __('landing.features') }}
                            </a>
                            <a href="#pricing" @click="mobileMenuOpen = false" class="block px-5 py-3 text-base font-medium text-gray-200 hover:text-white hover:bg-white/5 transition-colors">
                                <i class="fa-solid fa-tags mr-3 text-purple-400"></i>
                                {{ __('landing.pricing') }}
                            </a>
                            
                            <!-- Mobile Language Switcher -->
                            <div class="px-5 py-3 border-t border-white/10">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-3">{{ __('common.language') }}</p>
                                <div class="flex space-x-2">
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                        <button type="submit" class="px-4 py-2 text-sm rounded-xl font-medium {{ app()->getLocale() == 'en' ? 'bg-purple-600 text-white' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} transition-colors">
                                            {{ __('common.english') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('language.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="bn">
                                        <button type="submit" class="px-4 py-2 text-sm rounded-xl font-medium {{ app()->getLocale() == 'bn' ? 'bg-purple-600 text-white' : 'bg-white/5 text-gray-300 hover:bg-white/10' }} transition-colors">
                                            {{ __('common.bangla') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="px-5 py-3 border-t border-white/10 space-y-2">
                                @if (Route::has('login'))
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all">
                                            <i class="fa-solid fa-gauge-high mr-2"></i>
                                            {{ __('common.dashboard') }}
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-3 bg-white/5 text-gray-200 font-semibold rounded-xl hover:bg-white/10 transition-colors">
                                            {{ __('auth.login') }}
                                        </a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all">
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

            <!-- Modern Footer -->
            <footer class="bg-gray-900 text-white">
                <div class="max-w-7xl mx-auto pt-16 pb-8 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                        <!-- Brand -->
                        <div class="lg:col-span-2">
                            <x-site-logo class="h-10 w-auto" />
                            <p class="mt-4 text-gray-400 text-sm max-w-md leading-relaxed">
                                {{ __('landing.hero_subtitle') }}
                            </p>
                            <div class="mt-6 flex space-x-4">
                                <a href="https://github.com/mdjoherul50" target="_blank" class="w-10 h-10 bg-white/5 hover:bg-purple-600 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
                                    <i class="fa-brands fa-github text-lg"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-white/5 hover:bg-purple-600 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
                                    <i class="fa-brands fa-facebook text-lg"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-white/5 hover:bg-purple-600 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300">
                                    <i class="fa-brands fa-linkedin text-lg"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div>
                            <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-6">{{ __('landing.features') }}</h4>
                            <ul class="space-y-3">
                                <li><a href="#features" class="text-gray-400 hover:text-purple-400 text-sm transition-colors">{{ __('landing.feature_member_title') }}</a></li>
                                <li><a href="#features" class="text-gray-400 hover:text-purple-400 text-sm transition-colors">{{ __('landing.feature_meal_title') }}</a></li>
                                <li><a href="#features" class="text-gray-400 hover:text-purple-400 text-sm transition-colors">{{ __('landing.feature_report_title') }}</a></li>
                                <li><a href="#features" class="text-gray-400 hover:text-purple-400 text-sm transition-colors">{{ __('landing.feature_rent_title') }}</a></li>
                            </ul>
                        </div>
                        
                        <!-- Contact -->
                        <div>
                            <h4 class="text-sm font-bold uppercase tracking-wider text-white mb-6">{{ __('landing.contact') }}</h4>
                            <ul class="space-y-3">
                                <li class="flex items-center text-gray-400 text-sm">
                                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-envelope text-purple-400"></i>
                                    </div>
                                    {{ __('landing.support_email') }}
                                </li>
                                <li class="flex items-center text-gray-400 text-sm">
                                    <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-brands fa-github text-purple-400"></i>
                                    </div>
                                    <a href="https://github.com/mdjoherul50" class="hover:text-purple-400 transition-colors" target="_blank">{{ __('landing.github') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center">
                        <p class="text-gray-500 text-sm">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('landing.all_rights_reserved') }}.
                        </p>
                        <p class="text-gray-500 text-sm mt-4 md:mt-0">
                            {{ __('landing.developed_by') }} <a href="https://github.com/mdjoherul50" class="text-purple-400 hover:text-purple-300 font-medium transition-colors" target="_blank">Jahirul Islam</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
