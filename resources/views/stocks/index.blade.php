<x-layouts.app title="Data Stok">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Stok Barang</h2>
            <p class="text-sm text-gray-500">
                Cabang:
                @role('owner')
                    {{ request('branch_id') ? $branches->find(request('branch_id'))?->nama_cabang : 'Semua Cabang' }}
                @else
                    {{ auth()->user()->branch->nama_cabang ?? '-' }}
                @endrole
            </p>
        </div>
        <div class="flex gap-2">
            @role('gudang|supervisor|owner')
                <x-button href="{{ route('stocks.in') }}" variant="success" size="sm">
                    <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                    Barang Masuk
                </x-button>
                <x-button href="{{ route('stocks.out') }}" variant="danger" size="sm">
                    <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                    Barang Keluar
                </x-button>
            @endrole
            <x-button href="{{ route('stocks.history') }}" variant="secondary" size="sm">
                <x-heroicon-o-clock class="w-4 h-4" />
                Riwayat
            </x-button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-card class="text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $stocks->total() }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Jenis Barang</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-2xl font-bold text-yellow-500">
                {{ $stocks->getCollection()->where('stok', '<=', 10)->where('stok', '>', 0)->count() }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Stok Menipis</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-2xl font-bold text-red-500">
                {{ $stocks->getCollection()->where('stok', 0)->count() }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Stok Habis</p>
        </x-card>
    </div>

    <x-card>

        {{-- Filter & Search --}}
        <div class="flex gap-3 mb-4 flex-wrap">

            {{-- Filter Cabang — hanya Owner --}}
            @role('owner')
                <form method="GET" class="flex gap-2 flex-wrap items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Filter Cabang
                        </label>
                        <select name="branch_id"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Semua Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">
                            Cari Barang
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nama atau kode barang..."
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 w-56" />
                    </div>
                    <x-button type="submit" variant="primary" size="sm">Cari</x-button>
                    @if (request('search') || request('branch_id'))
                        <x-button href="{{ route('stocks.index') }}" variant="secondary" size="sm">
                            Reset
                        </x-button>
                    @endif
                </form>
            @else
                {{-- Non-owner: search saja --}}
                <form method="GET" class="flex gap-2 items-end">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..."
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" />
                    <x-button type="submit" variant="primary" size="sm">Cari</x-button>
                    @if (request('search'))
                        <x-button href="{{ route('stocks.index') }}" variant="secondary" size="sm">
                            Reset
                        </x-button>
                    @endif
                </form>
            @endrole
        </div>

        @role('owner')
            {{-- Tabel Owner: ada kolom Cabang --}}
            <x-table :headers="['Kode Barang', 'Nama Barang', 'Kategori', 'Cabang', 'Harga', 'Stok', 'Status']">
                @forelse($stocks as $stock)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-mono text-indigo-700 text-sm">
                            {{ $stock->product->kode_barang }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $stock->product->nama_barang }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge color="gray">{{ $stock->product->kategori }}</x-badge>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $stock->branch->nama_cabang }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            Rp {{ number_format($stock->product->harga, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-4 py-3 font-bold
                            {{ $stock->stok === 0 ? 'text-red-600' : ($stock->stok <= 10 ? 'text-yellow-600' : 'text-gray-800') }}">
                            {{ number_format($stock->stok) }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($stock->stok === 0)
                                <x-badge color="red">Habis</x-badge>
                            @elseif($stock->stok <= 10)
                                <x-badge color="yellow">Menipis</x-badge>
                            @else
                                <x-badge color="green">Aman</x-badge>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Belum ada data stok.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        @else
            {{-- Tabel Non-Owner: tanpa kolom Cabang --}}
            <x-table :headers="['Kode Barang', 'Nama Barang', 'Kategori', 'Harga', 'Stok', 'Status']">
                @forelse($stocks as $stock)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-mono text-indigo-700 text-sm">
                            {{ $stock->product->kode_barang }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $stock->product->nama_barang }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge color="gray">{{ $stock->product->kategori }}</x-badge>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            Rp {{ number_format($stock->product->harga, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-4 py-3 font-bold
                            {{ $stock->stok === 0 ? 'text-red-600' : ($stock->stok <= 10 ? 'text-yellow-600' : 'text-gray-800') }}">
                            {{ number_format($stock->stok) }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($stock->stok === 0)
                                <x-badge color="red">Habis</x-badge>
                            @elseif($stock->stok <= 10)
                                <x-badge color="yellow">Menipis</x-badge>
                            @else
                                <x-badge color="green">Aman</x-badge>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            Belum ada data stok.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        @endrole

        <div class="mt-4">
            {{ $stocks->links() }}
        </div>

    </x-card>

</x-layouts.app>
