<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fa-solid fa-user-tie mr-2"></i>
                {{ __('chat.all_managers') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">
                        {{ __('Select a mess manager to start a private conversation') ?? 'Select a mess manager to start a private conversation' }}
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($managers as $manager)
                            <a href="{{ route('chat.private', $manager) }}" class="block p-4 border border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-md transition-all">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 h-14 w-14 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($manager->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-gray-900 truncate">
                                            {{ $manager->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 truncate">
                                            {{ $manager->email }}
                                        </p>
                                        @if($manager->tenant)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fa-solid fa-building text-gray-400 mr-1"></i>
                                                {{ $manager->tenant->name }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-comment-dots text-2xl text-indigo-600"></i>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-2 text-center py-12">
                                <i class="fa-solid fa-user-slash text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">{{ __('No managers found') ?? 'No managers found' }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
