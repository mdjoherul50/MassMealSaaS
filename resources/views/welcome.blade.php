@php
    $plans = \App\Models\Plan::active()->ordered()->get();
@endphp

<x-landing-layout>
    <!-- Hero Section - Modern Gradient -->
    <section class="relative min-h-[90vh] flex items-center bg-gradient-to-br from-gray-900 via-purple-900 to-indigo-900 overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.05)\"%3E%3C/path%3E%3C/svg%3E')] opacity-40"></div>
            <div class="absolute top-1/4 -left-48 w-96 h-96 bg-purple-500/30 rounded-full blur-[128px]"></div>
            <div class="absolute bottom-1/4 -right-48 w-96 h-96 bg-indigo-500/30 rounded-full blur-[128px]"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-8">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-sm font-medium text-gray-200">{{ __('landing.trusted_by') }}</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight">
                    <span class="text-white">{{ __('landing.hero_title_1') }}</span>
                    <br>
                    <span class="bg-gradient-to-r from-purple-400 via-pink-400 to-indigo-400 bg-clip-text text-transparent">{{ __('landing.hero_title_2') }}</span>
                </h1>
                
                <p class="mt-6 max-w-2xl mx-auto text-lg sm:text-xl text-gray-300 leading-relaxed">
                    {{ __('landing.hero_subtitle') }}
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl hover:from-purple-500 hover:to-indigo-500 shadow-2xl shadow-purple-500/25 transition-all duration-300 hover:scale-105">
                        <i class="fa-solid fa-rocket mr-2 group-hover:animate-bounce"></i>
                        {{ __('landing.get_started_free') }}
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl hover:bg-white/20 transition-all duration-300">
                        <i class="fa-solid fa-play-circle mr-2"></i>
                        {{ __('landing.learn_more') }}
                    </a>
                </div>
                
                <p class="mt-6 text-sm text-gray-400 flex items-center justify-center gap-4">
                    <span class="flex items-center"><i class="fa-solid fa-check-circle text-green-400 mr-1"></i> {{ __('auth.no_credit_card') }}</span>
                    <span class="flex items-center"><i class="fa-solid fa-shield-halved text-green-400 mr-1"></i> {{ __('landing.secure') }}</span>
                </p>
            </div>
            
            <!-- Stats -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <div class="text-center p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <div class="text-3xl font-bold text-white">100+</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('landing.active_mess') }}</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <div class="text-3xl font-bold text-white">500+</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('landing.happy_users') }}</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <div class="text-3xl font-bold text-white">24/7</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('landing.support') }}</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10">
                    <div class="text-3xl font-bold text-white">99%</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('landing.uptime') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-700">
                    <i class="fa-solid fa-sparkles mr-2"></i>
                    {{ __('landing.features') }}
                </span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900">
                    {{ __('landing.features_title') }}
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    {{ __('landing.features_subtitle') }}
                </p>
            </div>
            
            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Cards -->
                @php
                $features = [
                    ['icon' => 'fa-users', 'gradient' => 'from-blue-500 to-indigo-600', 'bg' => 'bg-blue-50', 'title' => __('landing.feature_member_title'), 'desc' => __('landing.feature_member_desc')],
                    ['icon' => 'fa-utensils', 'gradient' => 'from-purple-500 to-pink-600', 'bg' => 'bg-purple-50', 'title' => __('landing.feature_meal_title'), 'desc' => __('landing.feature_meal_desc')],
                    ['icon' => 'fa-chart-pie', 'gradient' => 'from-emerald-500 to-teal-600', 'bg' => 'bg-emerald-50', 'title' => __('landing.feature_report_title'), 'desc' => __('landing.feature_report_desc')],
                    ['icon' => 'fa-house', 'gradient' => 'from-orange-500 to-amber-600', 'bg' => 'bg-orange-50', 'title' => __('landing.feature_rent_title'), 'desc' => __('landing.feature_rent_desc')],
                    ['icon' => 'fa-globe', 'gradient' => 'from-cyan-500 to-blue-600', 'bg' => 'bg-cyan-50', 'title' => __('landing.feature_language_title'), 'desc' => __('landing.feature_language_desc')],
                    ['icon' => 'fa-shield-halved', 'gradient' => 'from-rose-500 to-red-600', 'bg' => 'bg-rose-50', 'title' => __('landing.feature_security_title'), 'desc' => __('landing.feature_security_desc')],
                ];
                @endphp
                
                @foreach($features as $feature)
                <div class="group relative bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-500 hover:-translate-y-2">
                    <div class="{{ $feature['bg'] }} w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $feature['gradient'] }} rounded-xl flex items-center justify-center">
                            <i class="fa-solid {{ $feature['icon'] }} text-xl text-white"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 bg-gradient-to-br from-gray-900 via-purple-900 to-indigo-900 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-[128px]"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-[128px]"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-white/10 text-purple-300 border border-purple-500/30">
                    <i class="fa-solid fa-lightbulb mr-2"></i>
                    {{ __('landing.how_it_works') }}
                </span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-white">
                    {{ __('landing.how_it_works_title') }}
                </h2>
                <p class="mt-4 text-lg text-gray-300">
                    {{ __('landing.how_it_works_subtitle') }}
                </p>
            </div>
            
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                @php
                $steps = [
                    ['num' => '1', 'gradient' => 'from-purple-500 to-indigo-600', 'title' => __('landing.step_1_title'), 'desc' => __('landing.step_1_desc')],
                    ['num' => '2', 'gradient' => 'from-pink-500 to-purple-600', 'title' => __('landing.step_2_title'), 'desc' => __('landing.step_2_desc')],
                    ['num' => '3', 'gradient' => 'from-indigo-500 to-blue-600', 'title' => __('landing.step_3_title'), 'desc' => __('landing.step_3_desc')],
                ];
                @endphp
                
                @foreach($steps as $index => $step)
                <div class="relative text-center group">
                    @if($index < 2)
                    <div class="hidden md:block absolute top-12 left-[60%] w-[80%] h-0.5 bg-gradient-to-r from-purple-500/50 to-transparent"></div>
                    @endif
                    
                    <div class="relative inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br {{ $step['gradient'] }} shadow-2xl shadow-purple-500/25 mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="text-4xl font-black text-white">{{ $step['num'] }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">{{ $step['title'] }}</h3>
                    <p class="text-gray-400 leading-relaxed max-w-xs mx-auto">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-700">
                    <i class="fa-solid fa-tags mr-2"></i>
                    {{ __('landing.pricing') }}
                </span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-gray-900">
                    {{ __('landing.pricing_title') }}
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    {{ __('landing.pricing_subtitle') }}
                </p>
            </div>
            
            @if($plans->count() > 0)
            <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($plans as $plan)
                <div class="relative group">
                    @if($plan->is_popular)
                    <div class="absolute -top-4 left-0 right-0 flex justify-center z-10">
                        <span class="px-4 py-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold rounded-full shadow-lg">
                            <i class="fa-solid fa-crown mr-1"></i>
                            {{ __('landing.most_popular') }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="h-full bg-white rounded-3xl p-8 shadow-sm {{ $plan->is_popular ? 'ring-2 ring-purple-500 shadow-xl shadow-purple-500/10' : 'border border-gray-200' }} hover:shadow-xl transition-all duration-300 flex flex-col">
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            <p class="mt-2 text-sm text-gray-500 min-h-[40px]">{{ $plan->description }}</p>
                            
                            <div class="mt-6 flex items-baseline">
                                <span class="text-4xl font-black text-gray-900">৳{{ number_format((float)$plan->price_monthly, 0) }}</span>
                                <span class="ml-2 text-gray-500">{{ __('landing.per_month') }}</span>
                            </div>
                            
                            @if($plan->trial_days > 0)
                            <p class="mt-2 text-sm font-medium text-purple-600">
                                <i class="fa-solid fa-gift mr-1"></i>
                                {{ $plan->trial_days }} {{ __('landing.trial_days') }}
                            </p>
                            @endif
                            
                            <ul class="mt-6 space-y-4">
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fa-solid fa-users text-purple-500 w-5 mr-3"></i>
                                    <span>{{ __('landing.max_members') }}: <strong>{{ $plan->max_members }}</strong></span>
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fa-solid fa-database text-purple-500 w-5 mr-3"></i>
                                    <span>{{ __('landing.max_storage') }}: <strong>{{ $plan->max_storage_mb >= 1000 ? ($plan->max_storage_mb / 1000) . ' GB' : $plan->max_storage_mb . ' MB' }}</strong></span>
                                </li>
                                @if($plan->features)
                                    @foreach($plan->features as $feature)
                                    <li class="flex items-start text-sm text-gray-600">
                                        <i class="fa-solid fa-check text-emerald-500 w-5 mr-3 mt-0.5"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        
                        <a href="{{ route('register') }}" class="mt-8 block w-full text-center px-6 py-3.5 rounded-xl font-semibold transition-all duration-300 {{ $plan->is_popular ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700 shadow-lg shadow-purple-500/25' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }}">
                            {{ __('landing.get_started') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-24 bg-gradient-to-br from-purple-600 via-indigo-600 to-purple-700 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.1)\"%3E%3C/path%3E%3C/svg%3E')]"></div>
        </div>
        
        <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white">
                {{ __('landing.cta_title') }}
            </h2>
            <p class="mt-6 text-xl text-purple-100">
                {{ __('landing.cta_subtitle') }}
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-purple-600 bg-white rounded-2xl hover:bg-gray-100 shadow-2xl transition-all duration-300 hover:scale-105">
                    <i class="fa-solid fa-rocket mr-2"></i>
                    {{ __('landing.start_free') }}
                </a>
                <a href="#pricing" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white border-2 border-white/30 rounded-2xl hover:bg-white/10 transition-all duration-300">
                    <i class="fa-solid fa-tags mr-2"></i>
                    {{ __('landing.view_plans') }}
                </a>
            </div>
        </div>
    </section>
</x-landing-layout>
