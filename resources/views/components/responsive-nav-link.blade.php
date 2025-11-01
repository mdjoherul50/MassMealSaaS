@props(['active'])

@php
$baseClasses = 'block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out';

$inactiveClasses = 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300';

$activeClasses = 'border-indigo-400 text-indigo-700 bg-indigo-50';

$classes = ($active ?? false)
            ? $baseClasses . ' ' . $activeClasses
            : $baseClasses . ' ' . $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
