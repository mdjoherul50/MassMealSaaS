@props(['active'])

@php
$baseClasses = 'group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 ease-out relative overflow-hidden';

$inactiveClasses = 'text-gray-400 hover:text-white hover:bg-white/10 hover:backdrop-blur-sm hover:translate-x-1';

$activeClasses = 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-500/30';

$classes = ($active ?? false)
            ? $baseClasses . ' ' . $activeClasses
            : $baseClasses . ' ' . $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($active ?? false)
    <span class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
    @endif
    <span class="relative z-10 flex items-center gap-3">
        {{ $slot }}
    </span>
    @if($active ?? false)
    <span class="absolute right-2 w-2 h-2 bg-white rounded-full animate-pulse"></span>
    @endif
</a>
