<x-layouts.app title="Edit Barang">

    <div class="max-w-xl">
        <x-card title="Form Edit Barang">
            <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input
                    label="Kode Barang"
                    name="kode_barang"
                    value="{{ old('kode_barang', $product->kode_barang) }}"
                    :error="$errors->first('kode_barang')"
                />

                <x-input
                    label="Nama Barang"
                    name="nama_barang"
                    value="{{ old('nama_barang', $product->nama_barang) }}"
                    :error="$errors->first('nama_barang')"
                />

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="kategori"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               {{ $errors->has('kategori') ? 'border-red-400 bg-red-50' : '' }}">
                        <option value="">Pilih Kategori</option>
                        @foreach(['Makanan','Minuman','Toiletri','Sembako','Bumbu','Snack','Lainnya'] as $kat)
                            <option value="{{ $kat }}"
                                {{ old('kategori', $product->kategori) === $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <x-input
                    label="Harga (Rp)"
                    name="harga"
                    type="number"
                    min="0"
                    value="{{ old('harga', $product->harga) }}"
                    :error="$errors->first('harga')"
                />

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" variant="primary">Update</x-button>
                    <x-button href="{{ route('products.index') }}" variant="secondary">Batal</x-button>
                </div>

            </form>
        </x-card>
    </div>

</x-layouts.app>