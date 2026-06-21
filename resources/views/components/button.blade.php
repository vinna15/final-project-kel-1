@props([
    'type'    => 'button',
    'variant' => 'primary',
    'size'    => 'md',
    'href'    => null,
])

@php
    $variants = [
        'primary'   => 'bg-teal-600 hover:bg-teal-700 text-white focus:ring-teal-500',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 focus:ring-slate-400',
        'danger'    => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success'   => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'warning'   => 'bg-amber-500 hover:bg-amber-600 text-white focus:ring-amber-400',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = 'inline-flex items-center gap-2 font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 '
        . ($variants[$variant] ?? $variants['primary']) . ' '
        . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif