<x-layouts.app title="Dashboard Supervisor">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <x-card class="text-center border-l-4 border-indigo-500">
            <p class="text-3xl font-bold text-indigo-600">{{ $transaksiHariIni }}</p>
            <p class="text-sm text-gray-500 mt-1">Transaksi Hari Ini</p>
        </x-card>

        <x-card class="text-center border-l-4 border-green-500">
            <p class="text-3xl font-bold text-green-600">{{ $barangMasuk }}</p>
            <p class="text-sm text-gray-500 mt-1">Barang Masuk Hari Ini</p>
        </x-card>

        <x-card class="text-center border-l-4 border-red-500">
            <p class="text-3xl font-bold text-red-600">{{ $barangKeluar }}</p>
            <p class="text-sm text-gray-500 mt-1">Barang Keluar Hari Ini</p>
        </x-card>

    </div>

</x-layouts.app>