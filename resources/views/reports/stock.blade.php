<x-layouts.app title="Laporan Stok">

    {{-- Filter --}}
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
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Jenis</label>
                <select name="jenis"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    <option value="masuk"
                        {{ request('jenis') === 'masuk' ? 'selected' : '' }}>
                        Masuk
                    </option>
                    <option value="keluar"
                        {{ request('jenis') === 'keluar' ? 'selected' : '' }}>
                        Keluar
                    </option>
                </select>
            </div>
            <div class="flex gap-2 items-end">
                <x-button type="submit" variant="primary" size="sm">Filter</x-button>
                <x-button href="{{ route('reports.stock') }}" variant="secondary" size="sm">
                    Reset
                </x-button>
            </div>
        </form>
    </x-card>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
        <x-card class="text-center">
            <p class="text-2xl font-bold text-green-600">
                {{ $histories->where('jenis', 'masuk')->sum('jumlah') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Total Masuk (periode ini)</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-2xl font-bold text-red-600">
                {{ $histories->where('jenis', 'keluar')->sum('jumlah') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Total Keluar (periode ini)</p>
        </x-card>
    </div>

    <div class="flex gap-2 mb-4">
        @can('report.print')
            <a href="{{ route('pdf.stock', request()->query()) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700
                  text-white text-sm font-medium rounded-lg transition">
                <x-heroicon-o-printer class="w-4 h-4" />
                Print PDF
            </a>
        @endcan
        @can('report.export')
            <a href="{{ route('excel.stock', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700
                  text-white text-sm font-medium rounded-lg transition">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                Export Excel
            </a>
        @endcan
    </div>

    <x-card title="Hasil Laporan Stok">
        <x-table :headers="['Tanggal', 'Barang', 'Jenis', 'Jumlah', 'Keterangan', 'Dicatat Oleh']">
            @forelse($histories as $h)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                        {{ $h->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800 text-sm">
                            {{ $h->product->nama_barang }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $h->product->kode_barang }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge color="{{ $h->jenis === 'masuk' ? 'green' : 'red' }}">
                            {{ ucfirst($h->jenis) }}
                        </x-badge>
                    </td>
                    <td class="px-4 py-3 font-semibold
                        {{ $h->jenis === 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $h->jenis === 'masuk' ? '+' : '-' }}{{ $h->jumlah }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">
                        {{ $h->keterangan ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $h->creator->name }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Tidak ada data pada periode ini.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $histories->links() }}
        </div>
    </x-card>

</x-layouts.app>