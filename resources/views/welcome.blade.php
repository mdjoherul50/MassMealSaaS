@php
    $plans = \App\Models\Plan::active()->ordered()->get();
@endphp

<x-landing-layout>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">{{ __('landing.hero_title_1') }}</span>
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ __('landing.hero_title_2') }}</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600 sm:text-xl">
                    {{ __('landing.hero_subtitle') }}
                </p>
                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-rocket mr-2"></i>
                        {{ __('landing.get_started_free') }}
                    </a>
                    <a href="#pricing" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-indigo-600 bg-white border-2 border-indigo-600 rounded-xl hover:bg-indigo-50 transition-all">
                        <i class="fa-solid fa-tags mr-2"></i>
                        {{ __('landing.view_plans') }}
                    </a>
                </div>
                <p class="mt-4 text-sm text-gray-500">
                    <i class="fa-solid fa-shield-check text-green-500 mr-1"></i>
                    {{ __('auth.no_credit_card') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-700">
                    <i class="fa-solid fa-sparkles mr-2"></i>
                    {{ __('landing.features') }}
                </span>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ __('landing.features_title') }}
                </h2>
            </div>
            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="relative p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-lg">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ __('landing.feature_member_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.feature_member_desc') }}</p>
                </div>

                <!-- Feature 2 -->
                <div class="relative p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg">
                        <i class="fa-solid fa-utensils text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ __('landing.feature_meal_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.feature_meal_desc') }}</p>
                </div>

                <!-- Feature 3 -->
                <div class="relative p-8 bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-r from-green-500 to-teal-500 text-white shadow-lg">
                        <i class="fa-solid fa-chart-pie text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ __('landing.feature_report_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.feature_report_desc') }}</p>
                </div>

                <!-- Feature 4 -->
                <div class="relative p-8 bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg">
                        <i class="fa-solid fa-house text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ __('landing.feature_rent_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.feature_rent_desc') }}</p>
                </div>

                <!-- Feature 5 -->
                <div class="relative p-8 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg">
                        <i class="fa-solid fa-globe text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ __('landing.feature_language_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.feature_language_desc') }}</p>
                </div>

                <!-- Feature 6 -->
                <div class="relative p-8 bg-gradient-to-br from-red-50 to-rose-50 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-gradient-to-r from-red-500 to-rose-500 text-white shadow-lg">
                        <i class="fa-solid fa-shield-halved text-xl"></i>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">{{ __('landing.feature_security_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.feature_security_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-24 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-700">
                    <i class="fa-solid fa-lightbulb mr-2"></i>
                    {{ __('landing.how_it_works') }}
                </span>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ __('landing.how_it_works_title') }}
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    {{ __('landing.how_it_works_subtitle') }}
                </p>
            </div>
            <div class="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Step 1 -->
                <div class="relative text-center">
                    <div class="flex items-center justify-center h-20 w-20 mx-auto rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-3xl font-bold shadow-xl">
                        1
                    </div>
                    <div class="absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-indigo-300 to-purple-300 hidden md:block" style="transform: translateX(50%);"></div>
                    <h3 class="mt-8 text-xl font-semibold text-gray-900">{{ __('landing.step_1_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.step_1_desc') }}</p>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center">
                    <div class="flex items-center justify-center h-20 w-20 mx-auto rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white text-3xl font-bold shadow-xl">
                        2
                    </div>
                    <div class="absolute top-10 left-1/2 w-full h-0.5 bg-gradient-to-r from-purple-300 to-pink-300 hidden md:block" style="transform: translateX(50%);"></div>
                    <h3 class="mt-8 text-xl font-semibold text-gray-900">{{ __('landing.step_2_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.step_2_desc') }}</p>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center">
                    <div class="flex items-center justify-center h-20 w-20 mx-auto rounded-full bg-gradient-to-r from-pink-500 to-rose-500 text-white text-3xl font-bold shadow-xl">
                        3
                    </div>
                    <h3 class="mt-8 text-xl font-semibold text-gray-900">{{ __('landing.step_3_title') }}</h3>
                    <p class="mt-3 text-gray-600">{{ __('landing.step_3_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                    <i class="fa-solid fa-tags mr-2"></i>
                    {{ __('landing.pricing') }}
                </span>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ __('landing.pricing_title') }}
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    {{ __('landing.pricing_subtitle') }}
                </p>
            </div>
            
            @if($plans->count() > 0)
                <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($plans as $plan)
                        <div class="relative bg-white rounded-2xl {{ $plan->is_popular ? 'ring-2 ring-indigo-600 shadow-xl scale-105' : 'border border-gray-200 shadow-lg' }} overflow-hidden hover:shadow-xl transition-all">
                            @if($plan->is_popular)
                                <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-2 text-sm font-semibold">
                                    <i class="fa-solid fa-star mr-1"></i>
                                    {{ __('landing.most_popular') }}
                                </div>
                            @endif
                            
                            <div class="p-8 {{ $plan->is_popular ? 'pt-14' : '' }}">
                                <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                                <p class="mt-2 text-sm text-gray-500 h-12">{{ $plan->description }}</p>
                                
                                <div class="mt-6">
                                    <span class="text-4xl font-extrabold text-gray-900">৳{{ number_format((float)$plan->price_monthly, 0) }}</span>
                                    <span class="text-gray-500">{{ __('landing.per_month') }}</span>
                                </div>
                                
                                @if($plan->trial_days > 0)
                                    <p class="mt-2 text-sm font-medium text-indigo-600">
                                        <i class="fa-solid fa-clock mr-1"></i>
                                        {{ $plan->trial_days }} {{ __('landing.trial_days') }}
                                    </p>
                                @endif
                                
                                <ul class="mt-6 space-y-3">
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i class="fa-solid fa-users text-indigo-500 w-5 mr-2"></i>
                                        {{ __('landing.max_members') }}: <strong class="ml-1">{{ $plan->max_members }}</strong>
                                    </li>
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i class="fa-solid fa-database text-indigo-500 w-5 mr-2"></i>
                                        {{ __('landing.max_storage') }}: <strong class="ml-1">{{ $plan->max_storage_mb >= 1000 ? ($plan->max_storage_mb / 1000) . ' GB' : $plan->max_storage_mb . ' MB' }}</strong>
                                    </li>
                                    @if($plan->features)
                                        @foreach($plan->features as $feature)
                                            <li class="flex items-start text-sm text-gray-600">
                                                <i class="fa-solid fa-check text-green-500 w-5 mr-2 mt-0.5"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                
                                <a href="{{ route('register') }}" class="mt-8 block w-full text-center px-6 py-3 rounded-xl font-semibold transition-all {{ $plan->is_popular ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow-lg' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                                    {{ __('landing.get_started') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- CTA Section -->
    <div class="relative bg-gradient-to-r from-indigo-600 to-purple-600 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        <div class="max-w-7xl mx-auto text-center py-20 px-4 sm:px-6 lg:px-8 relative">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                {{ __('landing.cta_title') }}
            </h2>
            <p class="mt-4 text-xl text-indigo-100">
                {{ __('landing.cta_subtitle') }}
            </p>
            <a href="{{ route('register') }}" class="mt-8 inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-indigo-600 bg-white rounded-xl hover:bg-gray-100 shadow-xl transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-rocket mr-2"></i>
                {{ __('landing.start_free') }}
            </a>
        </div>
    </div>
</x-landing-layout>

<style>
    .bg-grid-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>
