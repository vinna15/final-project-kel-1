<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Minimarket Jayusman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white">Minimarket Jayusman</h1>
            {{-- <p class="text-slate-400 text-sm mt-1">Sistem Informasi Multi Cabang</p> --}}
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <h2 class="text-lg font-semibold text-slate-700 mb-6 text-center">
                Masuk ke Akun Anda
            </h2>

            @if(session('status'))
                <div class="mb-4 text-sm text-green-600 bg-green-50 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">
                        Email
                    </label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}" required autofocus
                        class="w-full rounded-lg border px-4 py-2.5 text-sm transition
                               focus:outline-none focus:ring-2 focus:ring-teal-500
                               {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}"
                        placeholder="email@minimarket.com" />
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="••••••••" />
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold
                           py-2.5 rounded-lg transition text-sm focus:outline-none
                           focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    Masuk
                </button>

            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; {{ date('Y') }} Minimarket Pak Jayusman. All rights reserved.
        </p>

    </div>

</body>
</html>