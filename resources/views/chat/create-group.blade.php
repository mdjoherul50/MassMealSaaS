<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fa-solid fa-users-plus mr-2"></i>
                {{ __('chat.create_group_chat') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <form action="{{ route('chat.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="group">

                    <!-- Group Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-signature mr-1 text-gray-400"></i>
                            {{ __('chat.group_name') }}
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="{{ __('chat.group_name') }}"
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Group Description (Optional) -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fa-solid fa-align-left mr-1 text-gray-400"></i>
                            {{ __('chat.group_description') }} ({{ __('common.optional') }})
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="{{ __('chat.group_description') }}"
                        >{{ old('description') }}</textarea>
                    </div>

                    <!-- Select Members -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fa-solid fa-user-check mr-1 text-gray-400"></i>
                            {{ __('chat.select_members') }}
                        </label>
                        
                        <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded-xl p-4">
                            @foreach($users as $user)
                                <label class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors">
                                    <input 
                                        type="checkbox" 
                                        name="participant_ids[]" 
                                        value="{{ $user->id }}"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    >
                                    <div class="ml-3 flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        
                        @error('participant_ids')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('chat.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            {{ __('common.cancel') }}
                        </a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                            <i class="fa-solid fa-check mr-2"></i>
                            {{ __('chat.create_group') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
