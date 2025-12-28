<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('tenant.edit_member') }}: {{ $member->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('members.update', $member) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="name" :value="__('common.name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $member->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('common.phone_optional')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $member->phone)" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('common.email_username')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $member->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="join_date" :value="__('tenant.joining_date_optional')" />
                            <x-text-input id="join_date" class="block mt-1 w-full" type="date" name="join_date" :value="old('join_date', $member->join_date)" />
                            <x-input-error :messages="$errors->get('join_date')" class="mt-2" />
                        </div>
                        
                        <p class="mt-4 text-sm text-gray-600">
                            ({{ __('tenant.member_password_note') }})
                        </p>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('members.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('common.cancel') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('common.update') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>