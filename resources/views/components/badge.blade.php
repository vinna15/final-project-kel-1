@props(['color' => 'gray'])

@php
    $colors = [
        'gray'   => 'bg-slate-100 text-slate-700',
        'green'  => 'bg-green-100 text-green-700',
        'red'    => 'bg-red-100 text-red-700',
        'yellow' => 'bg-amber-100 text-amber-700',
        'blue'   => 'bg-blue-100 text-blue-700',
        'indigo' => 'bg-teal-100 text-teal-700',
        'teal'   => 'bg-teal-100 text-teal-700',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
               . ($colors[$color] ?? $colors['gray'])
]) }}>
    {{ $slot }}
</span>