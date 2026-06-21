<x-layouts.app title="Laporan Transaksi">

    {{-- Filter --}}
    <x-card class="mb-4">
        <form method="GET" class="flex items-end gap-3 flex-wrap">
            <x-input label="Dari Tanggal" name="dari" type="date" value="{{ request('dari') }}" />
            <x-input label="Sampai" name="sampai" type="date" value="{{ request('sampai') }}" />
            <div class="flex gap-2 items-end">
                <x-button type="submit" variant="primary" size="sm">Filter</x-button>
                <x-button href="{{ route('reports.transaction') }}" variant="secondary" size="sm">
                    Reset
                </x-button>
                <a href="{{ route('pdf.transaction', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700
                          text-white text-sm font-medium rounded-lg transition">
                    <x-heroicon-o-printer class="w-4 h-4" />
                    Print PDF
                </a>
                @role('owner|manager')
                    <a href="{{ route('excel.transaction', request()->query()) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700
                              text-white text-sm font-medium rounded-lg transition">
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        Export Excel
                    </a>
                @endrole
            </div>
        </form>
    </x-card>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <x-card class="text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $transactions->total() }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Transaksi</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-2xl font-bold text-green-600">
                Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Total Penjualan</p>
        </x-card>
    </div>

    <x-card title="Hasil Laporan">
        <x-table :headers="['Kode', 'Cabang', 'Kasir', 'Tanggal', 'Total', 'Aksi']">
            @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-indigo-700 text-sm">
                        {{ $trx->kode_transaksi }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $trx->branch->nama_cabang }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $trx->kasir->name }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $trx->tanggal->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-800">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        <x-button href="{{ route('transactions.show', $trx) }}" variant="secondary" size="sm">
                            Detail
                        </x-button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Tidak ada data.
                    </td>
                </tr>
            @endforelse
        </x-table>

        {{-- Grand Total  --}}
        @if ($transactions->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                <span class="text-sm text-gray-500">
                    Menampilkan {{ $transactions->count() }} dari {{ $transactions->total() }} transaksi
                </span>
                <span class="font-bold text-gray-700">
                    Total Halaman Ini:
                    <span class="text-green-600">
                        Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}
                    </span>
                </span>
            </div>
        @endif

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </x-card>

</x-layouts.app>
