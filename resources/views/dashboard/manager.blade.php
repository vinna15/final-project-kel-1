<x-layouts.app title="Dashboard Manager">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        <x-card class="text-center">
            <p class="text-3xl font-bold text-teal-600">{{ $totalTransaksi }}</p>
            <p class="text-sm text-slate-500 mt-1">Total Transaksi</p>
        </x-card>

        <x-card class="text-center">
            <p class="text-2xl font-bold text-green-600">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
            <p class="text-sm text-slate-500 mt-1">Total Penjualan</p>
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
            <div class="flex items-center gap-2 justify-center py-4 text-green-600">
                <x-heroicon-o-check-circle class="w-5 h-5" />
                <p class="text-sm">Semua stok dalam kondisi aman.</p>
            </div>
        @else
            <x-table :headers="['Barang', 'Stok Tersisa']">
                @foreach($stokMenipis as $s)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">
                            {{ $s->product->nama_barang }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge color="{{ $s->stok === 0 ? 'red' : 'yellow' }}">
                                {{ $s->stok }} tersisa
                            </x-badge>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </x-card>

</x-layouts.app>