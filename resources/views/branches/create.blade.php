<x-layouts.app title="Tambah Cabang">

    <div class="max-w-xl">
        <x-card title="Form Tambah Cabang">
            <form method="POST" action="{{ route('branches.store') }}" class="space-y-4">
                @csrf

                <x-input
                    label="Kode Cabang"
                    name="kode"
                    value="{{ old('kode') }}"
                    placeholder="CBG-001"
                    :error="$errors->first('kode')"
                />

                <x-input
                    label="Nama Cabang"
                    name="nama_cabang"
                    value="{{ old('nama_cabang') }}"
                    placeholder="Minimarket Cabang Bandung"
                    :error="$errors->first('nama_cabang')"
                />

                <x-input
                    label="Kota"
                    name="kota"
                    value="{{ old('kota') }}"
                    placeholder="Bandung"
                    :error="$errors->first('kota')"
                />

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500
                               {{ $errors->has('alamat') ? 'border-red-400' : '' }}"
                        placeholder="Jl. Sudirman No. 10">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" variant="primary">Simpan</x-button>
                    <x-button href="{{ route('branches.index') }}" variant="secondary">Batal</x-button>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>