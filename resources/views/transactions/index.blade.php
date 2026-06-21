<x-layouts.app title="Daftar Transaksi">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Daftar Transaksi</h2>
            <p class="text-sm text-gray-500">Total {{ $transactions->total() }} transaksi</p>
        </div>
        @role('kasir')
            <x-button href="{{ route('transactions.create') }}" variant="primary">
                + Transaksi Baru
            </x-button>
        @endrole
    </div>

    {{-- Filter Tanggal --}}
    <x-card class="mb-4">
        <form method="GET" class="flex items-end gap-3 flex-wrap">
            <x-input
                label="Dari Tanggal"
                name="dari"
                type="date"
                value="{{ request('dari') }}"
            />
            <x-input
                label="Sampai"
                name="sampai"
                type="date"
                value="{{ request('sampai') }}"
            />
            <div class="flex gap-2 items-end">
                <x-button type="submit" variant="primary" size="sm">Filter</x-button>
                <x-button href="{{ route('transactions.index') }}" variant="secondary" size="sm">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>

    <x-card>
        <x-table :headers="['Kode Transaksi', 'Cabang', 'Kasir', 'Tanggal', 'Total', 'Aksi']">
            @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <span class="font-mono text-indigo-700 text-sm font-semibold">
                            {{ $trx->kode_transaksi }}
                        </span>
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
                        <div class="flex items-center gap-2">
                            <x-button
                                href="{{ route('transactions.show', $trx) }}"
                                variant="secondary"
                                size="sm">
                                Detail
                            </x-button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </x-card>

</x-layouts.app>