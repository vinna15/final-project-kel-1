@props(['title' => 'Dashboard'])

<header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">

    <h2 class="text-lg font-semibold text-slate-700">{{ $title }}</h2>

    <div class="flex items-center gap-3">
        <span class="text-sm text-slate-400">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </span>
        <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center
                    text-white text-sm font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>

</header>