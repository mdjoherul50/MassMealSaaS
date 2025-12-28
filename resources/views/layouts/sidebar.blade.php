<div class="h-full flex flex-col relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-purple-900/20 to-slate-900"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
    
    <!-- Glowing orbs for visual effect -->
    <div class="absolute top-20 -left-20 w-40 h-40 bg-purple-600/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-40 -right-20 w-40 h-40 bg-indigo-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    
    <!-- Logo Section -->
    <div class="relative shrink-0 flex items-center justify-center px-6 py-8">
        <div class="absolute inset-x-4 bottom-0 h-px bg-gradient-to-r from-transparent via-purple-500/50 to-transparent"></div>
        <a href="{{ route('dashboard') }}" class="group relative">
            <div class="absolute -inset-2 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl blur opacity-0 group-hover:opacity-40 transition-all duration-500"></div>
            <div class="relative bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10 group-hover:border-purple-500/50 transition-all duration-300">
                <x-tenant-logo class="block h-10 w-auto" />
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="relative flex-1 px-4 py-6 space-y-2 overflow-y-auto scrollbar-thin scrollbar-thumb-purple-600/50 scrollbar-track-transparent">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <i class="fa-solid fa-home w-6 text-center"></i>
            <span>{{ __('common.dashboard') }}</span>
        </x-nav-link>

        {{-- Chat Menu --}}
        <x-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')">
            <i class="fa-solid fa-comments w-6 text-center"></i>
            <span>{{ __('chat.chat') }}</span>
        </x-nav-link>

       @if(Auth::user()->role?->slug == 'member')
            <x-nav-link :href="route('members.myStatement')" :active="request()->routeIs('members.show')">
                <i class="fa-solid fa-id-card w-6 text-center"></i>
                <span>{{ __('common.my_statement') }}</span>
            </x-nav-link>
            @can('houserent.view')
            <x-nav-link :href="route('house-rents.my')" :active="request()->routeIs('house-rents.my')">
                <i class="fa-solid fa-house-user w-6 text-center"></i>
                <span>{{ __('house_rent.my_house_rent') }}</span>
            </x-nav-link>
            @endcan
        @endif

        @if(Auth::user()->role && Auth::user()->role->slug != 'super-admin')
        @can('members.view')
        <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
            <i class="fa-solid fa-users w-6 text-center"></i>
            <span>{{ __('tenant.members') }}</span>
        </x-nav-link>
        @endcan

        @if(Auth::user()->role?->slug === 'mess-admin')
        <x-nav-link :href="route('tenant.branding.logo.edit')" :active="request()->routeIs('tenant.branding.logo.*')">
            <i class="fa-solid fa-image w-6 text-center"></i>
            <span>{{ __('common.tenant_logo') }}</span>
        </x-nav-link>
        @endif

        @can('meals.view')
        <x-nav-link :href="route('meals.bulkEntry')" :active="request()->routeIs('meals.bulkEntry')">
             <i class="fa-solid fa-calendar-plus w-6 text-center"></i>
            <span>{{ __('common.daily_meal_entry') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('meals.index')" :active="request()->routeIs('meals.index') || request()->routeIs('meals.create') || request()->routeIs('meals.edit') || request()->routeIs('meals.show')">
             <i class="fa-solid fa-list-ol w-6 text-center"></i>
            <span>{{ __('common.all_meal_list') }}</span>
        </x-nav-link>
        @endcan

        @can('bazars.view')
        <x-nav-link :href="route('bazars.index')" :active="request()->routeIs('bazars.*')">
             <i class="fa-solid fa-shopping-cart w-6 text-center"></i>
            <span>{{ __('common.bazar_list') }}</span>
        </x-nav-link>
        @endcan

        @can('deposits.view')
        <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.*')">
             <i class="fa-solid fa-dollar-sign w-6 text-center"></i>
            <span>{{ __('common.deposits') }}</span>
        </x-nav-link>
        @endcan

        @can('houserent.manage')
        <x-nav-link :href="route('house-rent-mains.index')" :active="request()->routeIs('house-rent-mains.*')">
            <i class="fa-solid fa-house-medical w-6 text-center"></i>
            <span>{{ __('common.rent_management') }}</span>
        </x-nav-link>
        @elsecan('houserent.view')
        <x-nav-link :href="route('house-rents.index')" :active="request()->routeIs('house-rents.*')">
            <i class="fa-solid fa-house w-6 text-center"></i>
            <span>{{ __('house_rent.house_rent') }}</span>
        </x-nav-link>
        @endcan

        @can('reports.view')
        <x-nav-link :href="route('reports.overview')" :active="request()->routeIs('reports.*')">
             <i class="fa-solid fa-chart-pie w-6 text-center"></i>
            <span>{{ __('common.reports') }}</span>
        </x-nav-link>
        @endcan
        @endif

        @can('tenants.manage')
            <div class="mt-8 pt-6 relative">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-amber-500/50 to-transparent"></div>
                <div class="flex items-center justify-center mb-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500/20 to-orange-500/20 rounded-full border border-amber-500/30">
                        <i class="fa-solid fa-crown text-amber-400 text-xs"></i>
                        <h6 class="text-xs text-amber-400 uppercase tracking-wider font-bold">{{ __('common.super_admin') }}</h6>
                    </div>
                </div>
                <div class="space-y-2">
                    <x-nav-link :href="route('superadmin.tenants.index')" :active="request()->routeIs('superadmin.tenants.*')">
                        <i class="fa-solid fa-building-user w-6 text-center"></i>
                        <span>{{ __('tenant.tenants') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('superadmin.plans.index')" :active="request()->routeIs('superadmin.plans.*')">
                        <i class="fa-solid fa-crown w-6 text-center"></i>
                        <span>{{ __('plan.plans') }}</span>
                    </x-nav-link>

                    @can('roles.manage')
                    <x-nav-link :href="route('superadmin.roles.index')" :active="request()->routeIs('superadmin.roles.*')">
                        <i class="fa-solid fa-shield-halved w-6 text-center"></i>
                        <span>{{ __('common.manage_roles') }}</span>
                    </x-nav-link>
                    @endcan

                    <x-nav-link :href="route('superadmin.branding.logo.edit')" :active="request()->routeIs('superadmin.branding.logo.*')">
                        <i class="fa-solid fa-image w-6 text-center"></i>
                        <span>{{ __('common.site_logo') }}</span>
                    </x-nav-link>
                </div>
            </div>
        @endcan
    </nav>
    
    <!-- Footer -->
    <div class="relative px-4 py-5">
        <div class="absolute inset-x-4 top-0 h-px bg-gradient-to-r from-transparent via-purple-500/30 to-transparent"></div>
        <div class="bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10">
            <div class="text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <p class="text-xs font-semibold text-white/80">{{ config('app.name') }}</p>
                </div>
                <p class="text-[10px] text-purple-300/60 font-mono">v1.0.0</p>
                <div class="mt-3 pt-3 border-t border-white/10">
                    <p class="text-[10px] text-gray-400">
                        {{ __('landing.developed_by') }}
                        <a href="https://github.com/mdjoherul50" class="text-purple-400 hover:text-purple-300 transition-colors" target="_blank">Jahirul Islam</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
