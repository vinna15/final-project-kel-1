<x-layouts.app title="Dashboard Kasir">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        <x-card class="text-center border-l-4 border-indigo-500">
            <p class="text-3xl font-bold text-indigo-600">{{ $transaksiHariIni }}</p>
            <p class="text-sm text-gray-500 mt-1">Transaksi Hari Ini</p>
        </x-card>

        <x-card class="text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-600">
                Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Penjualan Hari Ini</p>
        </x-card>

    </div>

    <div class="flex gap-3">
        <x-button href="{{ route('transactions.create') }}" variant="primary">
            <x-heroicon-o-plus-circle class="w-4 h-4" />
            Buat Transaksi Baru
        </x-button>

        <x-button href="{{ route('transactions.index') }}" variant="secondary">
            <x-heroicon-o-receipt-percent class="w-4 h-4" />
            Riwayat Transaksi
        </x-button>
    </div>

</x-layouts.app>
