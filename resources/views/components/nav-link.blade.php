@props(['active'])

@php
$baseClasses = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out';

$inactiveClasses = 'text-gray-400 hover:bg-gray-800 hover:text-white';

$activeClasses = 'bg-gray-950 text-white';

$classes = ($active ?? false)
            ? $baseClasses . ' ' . $activeClasses
            : $baseClasses . ' ' . $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
