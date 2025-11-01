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

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-gray-100">

            <aside
                class="w-64 bg-gray-900 text-white flex-shrink-0 fixed inset-y-0 left-0 transform sm:relative sm:translate-x-0 transition-transform duration-200 ease-in-out z-30"
                :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
            >
                @include('layouts.sidebar')
            </aside>

            <div class="flex-1 flex flex-col h-screen overflow-y-auto">

                @include('layouts.topbar')

                @if (isset($header))
                    <header class="bg-white shadow sticky top-0 z-20">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 p-6">

                    @if (session('success'))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
                                <p class="font-bold">Success</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md" role="alert">
                                <p class="font-bold">Error</p>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    {{ $slot }}
                </main>

                @include('layouts.footer')
            </div>

             <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-20 sm:hidden" x-cloak></div>
        </div>
    </body>
</html>
