<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('chat.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-arrow-left text-gray-700"></i>
            </a>
            <h2 class="font-bold text-xl text-gray-900">
                <i class="fa-solid fa-users mr-2"></i>
                {{ __('chat.members') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-6">
                        {{ __('chat.select_member_to_start') }}
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($members as $member)
                            <a href="{{ route('chat.private', $member) }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:shadow-lg transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0 relative">
                                        @if($member->profile_photo)
                                            <img src="{{ $member->profile_photo_url }}" alt="{{ $member->name }}" class="w-14 h-14 rounded-full object-cover shadow-md group-hover:scale-110 transition-transform">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-110 transition-transform">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        @if($member->is_online)
                                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-gray-900 truncate">
                                            {{ $member->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 truncate">
                                            {{ $member->email }}
                                        </p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <p class="text-xs text-gray-500">
                                                <i class="fa-solid fa-user-tag text-gray-400 mr-1"></i>
                                                {{ $member->role->name ?? 'Member' }}
                                            </p>
                                            @if($member->is_online)
                                                <span class="text-xs text-green-600 font-medium flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                    {{ __('chat.online') }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">
                                                    {{ __('chat.last_seen') }} {{ $member->last_seen_at ? $member->last_seen_at->diffForHumans() : __('chat.never') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-comment-dots text-2xl text-indigo-600 group-hover:scale-110 transition-transform"></i>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-2 text-center py-12">
                                <i class="fa-solid fa-user-slash text-5xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">{{ __('chat.no_members_found') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
