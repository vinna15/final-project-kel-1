@props(['type' => 'success', 'message'])

@php
    $styles = [
        'success' => 'bg-green-50 border-green-500 text-green-800',
        'error'   => 'bg-red-50 border-red-500 text-red-800',
        'warning' => 'bg-amber-50 border-amber-500 text-amber-800',
        'info'    => 'bg-blue-50 border-blue-500 text-blue-800',
    ];

    $iconColors = [
        'success' => 'text-green-500',
        'error'   => 'text-red-500',
        'warning' => 'text-amber-500',
        'info'    => 'text-blue-500',
    ];
@endphp

<div class="flex items-start gap-3 border-l-4 rounded-lg px-4 py-3 mb-4 text-sm
            {{ $styles[$type] ?? $styles['info'] }}"
     x-data="{ show: true }"
     x-show="show"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <span class="flex-shrink-0 mt-0.5 {{ $iconColors[$type] ?? $iconColors['info'] }}">
        @if($type === 'success')
            <x-heroicon-o-check-circle class="w-5 h-5" />
        @elseif($type === 'error')
            <x-heroicon-o-x-circle class="w-5 h-5" />
        @elseif($type === 'warning')
            <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
        @else
            <x-heroicon-o-information-circle class="w-5 h-5" />
        @endif
    </span>

    <p class="flex-1 leading-relaxed">{{ $message }}</p>

    <button @click="show = false"
            class="flex-shrink-0 mt-0.5 opacity-50 hover:opacity-100 transition">
        <x-heroicon-o-x-mark class="w-4 h-4" />
    </button>

</div>

<script src="{{ asset('js/alpine.min.js') }}" defer></script>