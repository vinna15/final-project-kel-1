<x-layouts.app title="Riwayat Stok">

    {{-- Filter --}}
    <x-card class="mb-4">
        <form method="GET" class="flex items-end gap-3 flex-wrap">

            {{-- Filter Cabang — hanya Owner --}}
            @role('owner')
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Cabang</label>
                    <select name="branch_id"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endrole

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis</label>
                <select name="jenis"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua</option>
                    <option value="masuk"  {{ request('jenis') === 'masuk'  ? 'selected' : '' }}>
                        Masuk
                    </option>
                    <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>
                        Keluar
                    </option>
                </select>
            </div>

            <x-button type="submit" variant="primary" size="sm">Filter</x-button>

            @if(request()->hasAny(['branch_id', 'jenis']))
                <x-button href="{{ route('stocks.history') }}" variant="secondary" size="sm">
                    Reset
                </x-button>
            @endif

        </form>
    </x-card>

    <x-card>

        {{-- Owner: ada kolom Cabang --}}
        @role('owner')
            <x-table :headers="['Tanggal', 'Cabang', 'Barang', 'Jenis', 'Jumlah', 'Keterangan', 'Dicatat Oleh']">
                @forelse($histories as $h)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            {{ $h->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $h->branch->nama_cabang }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $h->product->nama_barang }}
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
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Belum ada riwayat.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        @else
            {{-- selain owner : tanpa kolom Cabang --}}
            <x-table :headers="['Tanggal', 'Barang', 'Jenis', 'Jumlah', 'Keterangan', 'Dicatat Oleh']">
                @forelse($histories as $h)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            {{ $h->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $h->product->nama_barang }}
                            <span class="block text-xs text-gray-400">
                                {{ $h->product->kode_barang }}
                            </span>
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
                            Belum ada riwayat.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        @endrole

        <div class="mt-4">
            {{ $histories->links() }}
        </div>
    </x-card>

</x-layouts.app>