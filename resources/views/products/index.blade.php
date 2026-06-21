<x-layouts.app title="Data Barang">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Barang</h2>
        @can('product.create')
            <x-button href="{{ route('products.create') }}" variant="primary">+ Tambah Barang</x-button>
        @endcan
    </div>

    <x-card>
        <x-table :headers="['Kode', 'Nama Barang', 'Kategori', 'Harga', 'Aksi']">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-indigo-700 text-sm">{{ $product->kode_barang }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $product->nama_barang }}</td>
                    <td class="px-4 py-3">
                        <x-badge color="gray">{{ $product->kategori }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-gray-700">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <x-button href="{{ route('products.show', $product) }}" variant="secondary" size="sm">
                                Stok
                            </x-button>
                            @can('product.edit')
                                <x-button href="{{ route('products.edit', $product) }}" variant="warning" size="sm">
                                    Edit
                                </x-button>
                            @endcan
                            @can('product.delete')
                                <form method="POST" action="{{ route('products.destroy', $product) }}"
                                      onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf @method('DELETE')
                                    <x-button type="submit" variant="danger" size="sm">Hapus</x-button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada barang.</td>
                </tr>
            @endforelse
        </x-table>
        <div class="mt-4">{{ $products->links() }}</div>
    </x-card>

</x-layouts.app>