<x-layouts.app title="Barang Masuk">
<div class="max-w-lg">
    <x-card title="Form Barang Masuk">
        <form method="POST" action="{{ route('stocks.in.store') }}" class="space-y-4">
            @csrf

            <x-select
                label="Pilih Barang"
                name="product_id"
                :options="$products->pluck('nama_barang', 'id')->toArray()"
                :error="$errors->first('product_id')"
                placeholder="Pilih Barang"
            />

            <x-input
                label="Jumlah Masuk"
                name="jumlah"
                type="number"
                min="1"
                value="{{ old('jumlah') }}"
                :error="$errors->first('jumlah')"
            />

            <x-input
                label="Keterangan"
                name="keterangan"
                value="{{ old('keterangan') }}"
                placeholder="Opsional"
            />

            <div class="flex gap-3 pt-2">
                <x-button type="submit" variant="success">Simpan Barang Masuk</x-button>
                <x-button href="{{ route('stocks.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
</div>
</x-layouts.app>