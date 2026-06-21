@props(['title' => null, 'class' => ''])

<div {{ $attributes->merge(['class' => "bg-white rounded-xl shadow-sm border border-slate-100 $class"]) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">
                {{ $title }}
            </h3>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>