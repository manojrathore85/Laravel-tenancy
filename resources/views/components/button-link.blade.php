@props(['href', 'type' => 'primary'])

@php
    $colors = [
        'primary' => 'bg-blue-500 hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-500 hover:bg-gray-700 focus:ring-gray-500',
        'info' => 'bg-sky-400 hover:bg-sky-700 focus:ring-sky-500',
        'alert' => 'bg-yellow-500 hover:bg-yellow-700 focus:ring-yellow-500',
        'success' => 'bg-green-500 hover:bg-green-700 focus:ring-green-500',
        'danger' => 'bg-red-500 hover:bg-red-700 focus:ring-red-500',
        'error' => 'bg-red-600 hover:bg-red-800 focus:ring-red-600',
    ];

    $class = $colors[$type] ?? $colors['primary'];
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class' => "inline-flex items-center px-4 py-2 border border-transparent rounded-full shadow-xl 
                font-semibold text-xs text-white uppercase tracking-widest focus:outline-none 
                focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 float-right $class"
]) }}>
    {{ $slot }}
</a>
