<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fa-solid fa-comments mr-2"></i>
                {{ __('chat.chats') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('chat.members') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-user-plus mr-2"></i>
                    {{ __('chat.new_chat') }}
                </a>
                @if(Auth::user()->role->slug === 'mess-admin' || Auth::user()->role->slug === 'super-admin')
                    <a href="{{ route('chat.create-group') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fa-solid fa-users-plus mr-2"></i>
                        {{ __('chat.create_group') }}
                    </a>
                @endif
                @if(Auth::user()->role->slug === 'super-admin')
                    <a href="{{ route('chat.managers') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fa-solid fa-user-tie mr-2"></i>
                        {{ __('chat.mess_managers') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($conversations->count() > 0)
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="divide-y divide-gray-200">
                        @foreach($conversations as $conversation)
                            @php
                                $otherParticipants = $conversation->participants->where('id', '!=', Auth::id());
                                $latestMessage = $conversation->latestMessage->first();
                            @endphp
                            <a href="{{ route('chat.show', $conversation) }}" class="block hover:bg-gray-50 transition-colors">
                                <div class="p-4 flex items-center space-x-4">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($conversation->isGroupChat())
                                            <div class="h-14 w-14 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                                <i class="fa-solid fa-users"></i>
                                            </div>
                                        @else
                                            @php
                                                $otherUser = $otherParticipants->first();
                                            @endphp
                                            <div class="relative">
                                                @if($otherUser && $otherUser->profile_photo)
                                                    <img src="{{ $otherUser->profile_photo_url }}" alt="{{ $otherUser->name }}" class="h-14 w-14 rounded-full object-cover">
                                                @else
                                                    <div class="h-14 w-14 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-lg">
                                                        {{ strtoupper(substr($otherUser->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                @endif
                                                @if($otherUser && $otherUser->is_online)
                                                    <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-base font-semibold text-gray-900 truncate">
                                                @if($conversation->isGroupChat())
                                                    {{ $conversation->name }}
                                                @else
                                                    {{ $otherParticipants->first()->name ?? __('chat.private_chat') }}
                                                @endif
                                            </h3>
                                            @if($latestMessage)
                                                <span class="text-xs text-gray-500">
                                                    {{ $latestMessage->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($latestMessage)
                                            <p class="text-sm text-gray-600 truncate mt-1">
                                                <span class="font-medium">{{ $latestMessage->user->name }}:</span>
                                                {{ $latestMessage->message ?: '📎 ' . __('chat.attachment') }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-400 italic mt-1">
                                                {{ __('chat.no_messages') }}
                                            </p>
                                        @endif
                                        
                                        @if($conversation->isGroupChat())
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fa-solid fa-users text-gray-400 mr-1"></i>
                                                {{ $conversation->participants->count() }} {{ __('chat.members') }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Unread Badge -->
                                    @if($conversation->unread_count > 0)
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-indigo-600 text-white text-xs font-bold">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-12 text-center">
                    <div class="flex flex-col items-center">
                        <div class="h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-comments text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('chat.no_conversations') }}</h3>
                        <p class="text-gray-600 mb-6">{{ __('chat.start_conversation') }}</p>
                        @if(Auth::user()->role->slug === 'mess-admin' || Auth::user()->role->slug === 'super-admin')
                            <a href="{{ route('chat.create-group') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fa-solid fa-plus mr-2"></i>
                                {{ __('chat.create_group') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
