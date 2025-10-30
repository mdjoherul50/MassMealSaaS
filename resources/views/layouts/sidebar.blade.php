<div class="py-4 px-6">
    <div class="shrink-0 flex items-center mb-6">
        <a href="{{ route('dashboard') }}">
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
        </a>
    </div>

    <nav class="space-y-1">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                    class="text-white hover:bg-gray-700">
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link :href="route('members.index')" :active="request()->routeIs('members.*')"
                    class="text-white hover:bg-gray-700">
            {{ __('Members') }}
        </x-nav-link>

        <x-nav-link :href="route('meals.bulkStoreView')" :active="request()->routeIs('meals.*')"
                    class="text-white hover:bg-gray-700">
            {{ __('Daily Meals') }}
        </x-nav-link>

        <x-nav-link :href="route('bazars.index')" :active="request()->routeIs('bazars.*')"
                    class="text-white hover:bg-gray-700">
            {{ __('Bazar List') }}
        </x-nav-link>
        
        <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.*')"
                    class="text-white hover:bg-gray-700">
            {{ __('Deposits') }}
        </x-nav-link>
        <x-nav-link :href="route('reports.overview')" :active="request()->routeIs('reports.*')"
                    class="text-white hover:bg-gray-700">
            {{ __('Reports') }}
        </x-nav-link>

        @if(Auth::user()->role == 'super_admin')
            <div class="mt-6 pt-4 border-t border-gray-700">
                <h6 class="px-4 text-xs text-gray-400 uppercase tracking-wider font-semibold">Super Admin</h6>
                <div class="mt-2 space-y-1">
                    <x-nav-link :href="route('superadmin.tenants.index')" :active="request()->routeIs('superadmin.tenants.*')"
                                class="text-white hover:bg-gray-700">
                        {{ __('Manage Tenants') }}
                    </x-nav-link>
                </div>
            </div>
        @endif
    </nav>
</div>