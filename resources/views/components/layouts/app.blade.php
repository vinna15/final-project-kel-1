<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Minimarket' }} — JayusmanShop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- <script src="https://unpkg.com/heroicons@2.0.18/dist/heroicons.js"></script> --}}
</head>
<body class="bg-slate-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        <x-sidebar />

        <div class="flex flex-col flex-1 overflow-hidden">
            <x-navbar :title="$title ?? 'Dashboard'" />

            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="{{ asset('js/chart.min.js') }}"></script>
    @stack('scripts')
</body>
</html>