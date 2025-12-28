<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur opacity-30 animate-pulse"></div>
                    <div class="relative w-14 h-14 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fa-solid fa-gauge-high text-white text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h2 class="font-black text-3xl bg-gradient-to-r from-gray-900 via-purple-900 to-indigo-900 bg-clip-text text-transparent">
                        {{ __('common.dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        {{ __('common.welcome_back') }}, <span class="font-semibold text-purple-600">{{ Auth::user()->name }}</span>
                    </p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-3">
                <div class="bg-gradient-to-br from-white to-purple-50 px-5 py-3 rounded-2xl border border-purple-100 shadow-lg">
                    <div class="text-[10px] text-purple-600 uppercase tracking-widest font-bold">{{ __('common.current_month') }}</div>
                    <div class="text-lg font-bold text-gray-900 mt-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-days text-purple-500"></i>
                        {{ now()->format('F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        $currentMonth = now()->format('Y-m');
        $startDate = \Carbon\Carbon::parse($currentMonth . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $totalBazar = \App\Models\Bazar::whereBetween('date', [$startDate, $endDate])->sum('total_amount');
        $totalMeal = \App\Models\Meal::whereBetween('date', [$startDate, $endDate])
            ->sum(\Illuminate\Support\Facades\DB::raw('breakfast + lunch + dinner'));
        $avgMealRate = $totalMeal > 0 ? $totalBazar / $totalMeal : 0;
        $totalDeposits = \App\Models\Deposit::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $mealNet = $totalDeposits - $totalBazar;

        $rentMain = \App\Models\HouseRentMain::where('month', $currentMonth)->first();
    @endphp

    <div class="py-6 bg-gradient-to-br from-slate-50 via-white to-purple-50/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Meal Statistics -->
            <div class="relative">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl blur opacity-40"></div>
                            <div class="relative w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-utensils text-white text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-900">
                                {{ __('common.meal_statistics') }}
                            </h3>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('common.this_month') }} Overview</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg shadow-green-500/30">
                            <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                            {{ __('common.live') }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Total Deposits Card -->
                    <div class="group relative">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative bg-gradient-to-br from-blue-500 via-blue-600 to-cyan-600 overflow-hidden rounded-3xl shadow-2xl">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                            <div class="p-6 text-white relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20">
                                        <i class="fa-solid fa-wallet text-2xl"></i>
                                    </div>
                                    <div class="text-[10px] font-bold bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full uppercase tracking-wider">
                                        {{ __('common.this_month') }}
                                    </div>
                                </div>
                                <h3 class="text-xs font-bold uppercase tracking-widest opacity-80 mb-3">{{ __('common.total_deposits') }}</h3>
                                <p class="text-4xl font-black tracking-tight">৳{{ number_format($totalDeposits, 0) }}</p>
                                <div class="mt-4 pt-4 border-t border-white/20">
                                    <div class="flex items-center text-xs opacity-80">
                                        <i class="fa-solid fa-arrow-up mr-1"></i>
                                        <span>{{ __('common.income') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Bazar Card -->
                    <div class="group relative">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative bg-gradient-to-br from-purple-500 via-purple-600 to-pink-600 overflow-hidden rounded-3xl shadow-2xl">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                            <div class="p-6 text-white relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20">
                                        <i class="fa-solid fa-cart-shopping text-2xl"></i>
                                    </div>
                                    <div class="text-[10px] font-bold bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full uppercase tracking-wider">
                                        {{ __('common.expenses') }}
                                    </div>
                                </div>
                                <h3 class="text-xs font-bold uppercase tracking-widest opacity-80 mb-3">{{ __('common.total_bazar') }}</h3>
                                <p class="text-4xl font-black tracking-tight">৳{{ number_format($totalBazar, 0) }}</p>
                                <div class="mt-4 pt-4 border-t border-white/20">
                                    <div class="flex items-center text-xs opacity-80">
                                        <i class="fa-solid fa-arrow-down mr-1"></i>
                                        <span>{{ __('common.spent') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meal Rate Card -->
                    <div class="group relative">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 overflow-hidden rounded-3xl shadow-2xl">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                            <div class="p-6 text-white relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20">
                                        <i class="fa-solid fa-calculator text-2xl"></i>
                                    </div>
                                    <div class="text-[10px] font-bold bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full uppercase tracking-wider">
                                        {{ __('common.per_meal') }}
                                    </div>
                                </div>
                                <h3 class="text-xs font-bold uppercase tracking-widest opacity-80 mb-3">{{ __('common.meal_rate') }}</h3>
                                <p class="text-4xl font-black tracking-tight">৳{{ number_format($avgMealRate, 2) }}</p>
                                <div class="mt-4 pt-4 border-t border-white/20">
                                    <div class="flex items-center text-xs opacity-80">
                                        <i class="fa-solid fa-utensils mr-1"></i>
                                        <span>{{ number_format($totalMeal) }} {{ __('common.meals') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Card -->
                    <div class="group relative">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-{{ $mealNet >= 0 ? 'amber' : 'rose' }}-500 to-{{ $mealNet >= 0 ? 'orange' : 'red' }}-600 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative bg-gradient-to-br from-{{ $mealNet >= 0 ? 'amber-500 via-orange-500 to-orange' : 'rose-500 via-red-500 to-red' }}-600 overflow-hidden rounded-3xl shadow-2xl">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
                            <div class="p-6 text-white relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="w-14 h-14 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20">
                                        <i class="fa-solid fa-{{ $mealNet >= 0 ? 'piggy-bank' : 'triangle-exclamation' }} text-2xl"></i>
                                    </div>
                                    <div class="text-[10px] font-bold bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full uppercase tracking-wider">
                                        {{ $mealNet >= 0 ? __('common.surplus') : __('common.deficit') }}
                                    </div>
                                </div>
                                <h3 class="text-xs font-bold uppercase tracking-widest opacity-80 mb-3">{{ __('common.balance') }}</h3>
                                <p class="text-4xl font-black tracking-tight">৳{{ number_format(abs($mealNet), 0) }}</p>
                                <div class="mt-4 pt-4 border-t border-white/20">
                                    <div class="flex items-center text-xs opacity-80">
                                        <i class="fa-solid fa-{{ $mealNet >= 0 ? 'check-circle' : 'exclamation-circle' }} mr-1"></i>
                                        <span>{{ $mealNet >= 0 ? __('common.extra') : __('common.deficit') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- House Rent Statistics -->
            <div class="relative">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-orange-600 to-rose-600 rounded-xl blur opacity-40"></div>
                            <div class="relative w-12 h-12 bg-gradient-to-br from-orange-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-house text-white text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-900">
                                {{ __('house_rent.house_rent_management') }}
                            </h3>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">{{ __('common.monthly_total') }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Rent -->
                    <div class="group relative bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-500 to-amber-500"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-amber-100 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                    <i class="fa-solid fa-building text-3xl text-orange-600"></i>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-orange-600 uppercase tracking-wider bg-orange-100 px-2 py-1 rounded-full">{{ __('common.monthly_total') }}</span>
                                </div>
                            </div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('house_rent.total_rent') }}</h3>
                            <p class="text-3xl font-black text-gray-900">
                                ৳{{ $rentMain ? number_format((float) $rentMain->total, 0) : '0' }}
                            </p>
                        </div>
                    </div>

                    <!-- Assigned to Members -->
                    <div class="group relative bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                    <i class="fa-solid fa-users text-3xl text-blue-600"></i>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider bg-blue-100 px-2 py-1 rounded-full">{{ __('common.distributed') }}</span>
                                </div>
                            </div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('house_rent.assigned_to_members') }}</h3>
                            <p class="text-3xl font-black text-gray-900">
                                ৳{{ $rentMain ? number_format((float) $rentMain->assigned_to_members, 0) : '0' }}
                            </p>
                        </div>
                    </div>

                    <!-- Remaining Balance -->
                    <div class="group relative bg-white rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'rose-500 to-red' : 'emerald-500 to-green' }}-500"></div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'rose' : 'emerald' }}-100 to-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'red' : 'green' }}-100 rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                    <i class="fa-solid fa-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'triangle-exclamation' : 'circle-check' }} text-3xl text-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'rose' : 'emerald' }}-600"></i>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'rose' : 'emerald' }}-600 uppercase tracking-wider bg-{{ $rentMain && $rentMain->remaining_balance > 0 ? 'rose' : 'emerald' }}-100 px-2 py-1 rounded-full">
                                        {{ $rentMain && $rentMain->remaining_balance > 0 ? __('common.pending') : __('common.settled') }}
                                    </span>
                                </div>
                            </div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('house_rent.remaining_balance') }}</h3>
                            <p class="text-3xl font-black {{ $rentMain && $rentMain->remaining_balance > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                ৳{{ $rentMain ? number_format(abs((float) $rentMain->remaining_balance), 0) : '0' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 rounded-[2rem] blur-xl opacity-30"></div>
                <div class="relative bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 overflow-hidden rounded-[2rem] shadow-2xl">
                    <!-- Animated background elements -->
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-500/30 rounded-full blur-3xl animate-pulse"></div>
                        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-fuchsia-500/30 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <div class="relative p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="relative">
                                <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 to-orange-500 rounded-xl blur opacity-60 animate-pulse"></div>
                                <div class="relative w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-bolt text-white text-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-2xl md:text-3xl font-black text-white">
                                    {{ __('common.quick_actions') }}
                                </h3>
                                <p class="text-purple-300 text-sm">{{ __('common.shortcuts') }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('reports.overview') }}" class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative flex items-center gap-4 px-5 py-5 bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl group-hover:border-transparent group-hover:bg-transparent transition-all duration-300">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-chart-pie text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="text-white font-bold block">{{ __('common.monthly_report') }}</span>
                                        <span class="text-purple-300 text-xs group-hover:text-white/80">{{ __('common.view_reports') }}</span>
                                    </div>
                                </div>
                            </a>
                            
                            @can('meals.manage')
                            <a href="{{ route('meals.bulkEntry') }}" class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative flex items-center gap-4 px-5 py-5 bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl group-hover:border-transparent group-hover:bg-transparent transition-all duration-300">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-utensils text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="text-white font-bold block">{{ __('common.add_meals') }}</span>
                                        <span class="text-purple-300 text-xs group-hover:text-white/80">{{ __('common.daily_entry') }}</span>
                                    </div>
                                </div>
                            </a>
                            @endcan
                            
                            @can('bazars.manage')
                            <a href="{{ route('bazars.create') }}" class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative flex items-center gap-4 px-5 py-5 bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl group-hover:border-transparent group-hover:bg-transparent transition-all duration-300">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-cart-shopping text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="text-white font-bold block">{{ __('common.add_bazar') }}</span>
                                        <span class="text-purple-300 text-xs group-hover:text-white/80">{{ __('common.new_expense') }}</span>
                                    </div>
                                </div>
                            </a>
                            @endcan
                            
                            @can('houserent.manage')
                            <a href="{{ route('house-rent-mains.index') }}" class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                                <div class="relative flex items-center gap-4 px-5 py-5 bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl group-hover:border-transparent group-hover:bg-transparent transition-all duration-300">
                                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-house text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="text-white font-bold block">{{ __('common.manage_rent') }}</span>
                                        <span class="text-purple-300 text-xs group-hover:text-white/80">{{ __('common.house_rent') }}</span>
                                    </div>
                                </div>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
