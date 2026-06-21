<x-layouts.app title="Tambah Barang">

    <div class="max-w-xl">
        <x-card title="Form Tambah Barang">
            <form method="POST" action="{{ route('products.store') }}" class="space-y-4">
                @csrf

                <x-input
                    label="Kode Barang"
                    name="kode_barang"
                    value="{{ old('kode_barang') }}"
                    placeholder="PRD-001"
                    :error="$errors->first('kode_barang')"
                />

                <x-input
                    label="Nama Barang"
                    name="nama_barang"
                    value="{{ old('nama_barang') }}"
                    placeholder="Indomie Goreng"
                    :error="$errors->first('nama_barang')"
                />

                <x-select
                    label="Kategori"
                    name="kategori"
                    :error="$errors->first('kategori')"
                    :options="[
                        'Makanan'  => 'Makanan',
                        'Minuman'  => 'Minuman',
                        'Toiletri' => 'Toiletri',
                        'Sembako'  => 'Sembako',
                        'Bumbu'    => 'Bumbu',
                        'Snack'    => 'Snack',
                        'Lainnya'  => 'Lainnya',
                    ]"
                    placeholder="Pilih Kategori"
                />

                <x-input
                    label="Harga (Rp)"
                    name="harga"
                    type="number"
                    min="0"
                    value="{{ old('harga') }}"
                    placeholder="5000"
                    :error="$errors->first('harga')"
                />

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" variant="primary">Simpan</x-button>
                    <x-button href="{{ route('products.index') }}" variant="secondary">Batal</x-button>
                </div>

            </form>
        </x-card>
    </div>

</x-layouts.app>