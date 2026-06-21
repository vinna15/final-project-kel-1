<x-layouts.app title="Detail Barang">

    <div class="mb-4">
        <x-button href="{{ route('products.index') }}" variant="secondary" size="sm">
            ← Kembali
        </x-button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info Produk --}}
        <div class="lg:col-span-1">
            <x-card title="Info Barang">
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Kode Barang</dt>
                        <dd class="font-mono text-indigo-700 font-semibold mt-0.5">
                            {{ $product->kode_barang }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Nama Barang</dt>
                        <dd class="font-medium text-gray-800 mt-0.5">{{ $product->nama_barang }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Kategori</dt>
                        <dd class="mt-0.5">
                            <x-badge color="gray">{{ $product->kategori }}</x-badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Harga Satuan</dt>
                        <dd class="font-bold text-gray-800 text-base mt-0.5">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>

                @can('product.edit')
                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <x-button
                            href="{{ route('products.edit', $product) }}"
                            variant="warning"
                            size="sm"
                            class="w-full justify-center">
                            ✏️ Edit Barang
                        </x-button>
                    </div>
                @endcan
            </x-card>
        </div>

        {{-- Stok per Cabang --}}
        <div class="lg:col-span-2">
            <x-card title="Stok per Cabang">
                <x-table :headers="['Cabang', 'Kota', 'Stok', 'Status']">
                    @forelse($stocks as $stock)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $stock->branch->nama_cabang }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $stock->branch->kota }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-700">
                                {{ number_format($stock->stok) }} pcs
                            </td>
                            <td class="px-4 py-3">
                                @if($stock->stok === 0)
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
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                Belum ada data stok.
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                {{-- Total Stok Semua Cabang --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Stok Seluruh Cabang</span>
                    <span class="font-bold text-indigo-700 text-lg">
                        {{ number_format($stocks->sum('stok')) }} pcs
                    </span>
                </div>
            </x-card>
        </div>

    </div>

</x-layouts.app>