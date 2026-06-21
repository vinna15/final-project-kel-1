{{-- resources/views/components/sidebar-item.blade.php --}}

@props(['route', 'icon', 'label'])

@php
    $isActive = request()->routeIs($route) || request()->routeIs($route . '.*');
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
          {{ $isActive
              ? 'bg-indigo-700 text-white font-semibold'
              : 'text-indigo-200 hover:bg-indigo-800 hover:text-white' }}">
    <span>{{ $icon }}</span>
    <span>{{ $label }}</span>
</a>