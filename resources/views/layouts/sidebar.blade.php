<div class="py-4 px-3">
    <div class="shrink-0 flex items-center justify-center px-4 mb-5">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
        </a>
    </div>

    <nav class="space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <i class="fa-solid fa-home w-6 text-center"></i>
            <span>{{ __('Dashboard') }}</span>
        </x-nav-link>
       @if(Auth::user()->role?->slug == 'member')
            <x-nav-link :href="route('members.myStatement')" :active="request()->routeIs('members.show')">
                <i class="fa-solid fa-id-card w-6 text-center"></i>
                <span>{{ __('My Statement') }}</span>
            </x-nav-link>
            @can('houserent.view')
            <x-nav-link :href="route('house-rents.my')" :active="request()->routeIs('house-rents.my')">
                <i class="fa-solid fa-house w-6 text-center"></i>
                <span>{{ __('My House Rent') }}</span>
            </x-nav-link>
            @endcan
        @endif

        @if(Auth::user()->role && Auth::user()->role->slug != 'super-admin')
        @can('members.view')
        <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')">
            <i class="fa-solid fa-users w-6 text-center"></i>
            <span>{{ __('Members') }}</span>
        </x-nav-link>
        @endcan

        @can('meals.view')
        <x-nav-link :href="route('meals.bulkEntry')" :active="request()->routeIs('meals.bulkEntry')">
             <i class="fa-solid fa-calendar-plus w-6 text-center"></i>
            <span>{{ __('Daily Meal Entry') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('meals.index')" :active="request()->routeIs('meals.index') || request()->routeIs('meals.create') || request()->routeIs('meals.edit') || request()->routeIs('meals.show')">
             <i class="fa-solid fa-list-ol w-6 text-center"></i>
            <span>{{ __('All Meal List') }}</span>
        </x-nav-link>
        @endcan

        @can('bazars.view')
        <x-nav-link :href="route('bazars.index')" :active="request()->routeIs('bazars.*')">
             <i class="fa-solid fa-shopping-cart w-6 text-center"></i>
            <span>{{ __('Bazar List') }}</span>
        </x-nav-link>
        @endcan

        @can('deposits.view')
        <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.*')">
             <i class="fa-solid fa-dollar-sign w-6 text-center"></i>
            <span>{{ __('Deposits') }}</span>
        </x-nav-link>
        @endcan

        @can('houserent.view')
        <x-nav-link :href="route('house-rents.index')" :active="request()->routeIs('house-rents.*')">
            <i class="fa-solid fa-house w-6 text-center"></i>
            <span>{{ __('House Rent') }}</span>
        </x-nav-link>
        @endcan

        @can('houserent.manage')
        <x-nav-link :href="route('house-rent-mains.index')" :active="request()->routeIs('house-rent-mains.*')">
            <i class="fa-solid fa-house-medical w-6 text-center"></i>
            <span>{{ __('Rent Management') }}</span>
        </x-nav-link>
        @endcan

        @can('reports.view')
        <x-nav-link :href="route('reports.overview')" :active="request()->routeIs('reports.*')">
             <i class="fa-solid fa-chart-pie w-6 text-center"></i>
            <span>{{ __('Reports') }}</span>
        </x-nav-link>
        @endcan
        @endif

        @if(Auth::user()->role && Auth::user()->role->slug == 'super-admin')
            <div class="mt-6 pt-4 border-t border-gray-700">
                <h6 class="px-3 text-xs text-gray-400 uppercase tracking-wider font-semibold">Super Admin</h6>
                <div class="mt-2 space-y-1">
                    @can('tenants.manage')
                    <x-nav-link :href="route('superadmin.tenants.index')" :active="request()->routeIs('superadmin.tenants.*')">
                        <i class="fa-solid fa-building-user w-6 text-center"></i>
                        <span>{{ __('Manage Tenants') }}</span>
                    </x-nav-link>
                    @endcan

                    @can('roles.manage')
                    <x-nav-link :href="route('superadmin.roles.index')" :active="request()->routeIs('superadmin.roles.*')">
                        <i class="fa-solid fa-shield-halved w-6 text-center"></i>
                        <span>{{ __('Manage Roles') }}</span>
                    </x-nav-link>
                    @endcan
                </div>
            </div>
        @endif
    </nav>
</div>
