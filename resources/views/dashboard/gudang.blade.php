<x-layouts.app title="Dashboard Gudang">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        <x-card class="text-center border-l-4 border-green-500">
            <p class="text-3xl font-bold text-green-600">{{ $barangMasuk }}</p>
            <p class="text-sm text-gray-500 mt-1">Barang Masuk Hari Ini</p>
        </x-card>

        <x-card class="text-center border-l-4 border-red-500">
            <p class="text-3xl font-bold text-red-600">{{ $barangKeluar }}</p>
            <p class="text-sm text-gray-500 mt-1">Barang Keluar Hari Ini</p>
        </x-card>

    </div>

    <x-card>
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-amber-500" />
            <h3 class="text-sm font-semibold text-slate-600 uppercase tracking-wide">
                Stok Menipis (≤ 10)
            </h3>
        </div>
        @if($stokMenipis->isEmpty())
            <p class="text-sm text-gray-400 text-center py-4">Semua stok aman.</p>
        @else
            <x-table :headers="['Barang', 'Stok']">
                @foreach($stokMenipis as $s)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $s->product->nama_barang }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge color="red">{{ $s->stok }}</x-badge>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </x-card>

</x-layouts.app>