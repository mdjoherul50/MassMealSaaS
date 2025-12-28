<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="sidebarOpen = ! sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="hidden sm:flex flex-1">
                </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                <!-- Language Switcher -->
                <div>
                    <x-language-switcher />
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition ease-in-out duration-150 shadow-sm">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover mr-2 border-2 border-gray-200">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-2 text-white font-semibold text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="text-left">
                                <div class="font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->role?->name }}</div>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fa-solid fa-user-circle mr-2 text-gray-400"></i>
                            {{ __('common.profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fa-solid fa-right-from-bracket mr-2 text-gray-400"></i>
                                {{ __('common.logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

             <div class="sm:hidden flex items-center ms-auto">
                 <div class="flex items-center">
                     @if(Auth::user()->profile_photo)
                         <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover mr-2 border-2 border-gray-200">
                     @else
                         <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-2 text-white font-semibold text-xs">
                             {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                         </div>
                     @endif
                     <div class="font-medium text-sm text-gray-700">{{ Auth::user()->name }}</div>
                 </div>
             </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" @click.away="open = false">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('common.dashboard') }}
            </x-responsive-nav-link>

       @if(Auth::user()->role?->slug == 'member')
            <x-nav-link :href="route('members.myStatement')" :active="request()->routeIs('members.show')">
                <i class="fa-solid fa-id-card w-6 text-center"></i>
                <span>{{ __('common.my_statement') }}</span>
            </x-nav-link>
            @can('houserent.view')
            <x-responsive-nav-link :href="route('house-rents.my')" :active="request()->routeIs('house-rents.my')">
                {{ __('house_rent.my_house_rent') }}
            </x-responsive-nav-link>
            @endcan
        @endif

        @if(Auth::user()->role && Auth::user()->role->slug != 'super-admin')
            @can('members.view')
            <x-responsive-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
                {{ __('tenant.members') }}
            </x-responsive-nav-link>
            @endcan

            @can('meals.view')
            <x-responsive-nav-link :href="route('meals.bulkEntry')" :active="request()->routeIs('meals.bulkEntry')">
                {{ __('common.daily_meal_entry') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('meals.index')" :active="request()->routeIs('meals.index') || request()->routeIs('meals.create') || request()->routeIs('meals.edit') || request()->routeIs('meals.show')">
                {{ __('common.all_meal_list') }}
            </x-responsive-nav-link>
            @endcan

            @can('bazars.view')
            <x-responsive-nav-link :href="route('bazars.index')" :active="request()->routeIs('bazars.*')">
                {{ __('common.bazar_list') }}
            </x-responsive-nav-link>
            @endcan

            @can('deposits.view')
            <x-responsive-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.*')">
                {{ __('common.deposits') }}
            </x-responsive-nav-link>
            @endcan

            @can('houserent.manage')
            <x-responsive-nav-link :href="route('house-rent-mains.index')" :active="request()->routeIs('house-rent-mains.*')">
                {{ __('common.rent_management') }}
            </x-responsive-nav-link>
            @elsecan('houserent.view')
            <x-responsive-nav-link :href="route('house-rents.index')" :active="request()->routeIs('house-rents.*')">
                {{ __('house_rent.house_rent') }}
            </x-responsive-nav-link>
            @endcan

            @can('reports.view')
            <x-responsive-nav-link :href="route('reports.overview')" :active="request()->routeIs('reports.*')">
                {{ __('common.reports') }}
            </x-responsive-nav-link>
            @endcan
        @endif

            @if(Auth::user()->role && Auth::user()->role->slug == 'super-admin')
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ __('common.super_admin') }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    @can('tenants.manage')
                    <x-responsive-nav-link :href="route('superadmin.tenants.index')" :active="request()->routeIs('superadmin.tenants.*')">
                        {{ __('common.manage_tenants') }}
                    </x-responsive-nav-link>
                    @endcan
                    @can('roles.manage')
                    <x-responsive-nav-link :href="route('superadmin.roles.index')" :active="request()->routeIs('superadmin.roles.*')">
                        {{ __('common.manage_roles') }}
                    </x-responsive-nav-link>
                    @endcan
                </div>
            </div>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <!-- Mobile Language Switcher -->
            <div class="px-4 py-2">
                <p class="text-xs font-medium text-gray-500 uppercase mb-2">{{ __('common.language') }}</p>
                <x-language-switcher />
            </div>

            <div class="px-4 mt-3">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('common.profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('common.logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
