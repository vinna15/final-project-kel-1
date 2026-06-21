<x-layouts.app title="Edit Cabang">

    <div class="max-w-xl">
        <x-card title="Form Edit Cabang">
            <form method="POST" action="{{ route('branches.update', $branch) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input label="Kode Cabang"  name="kode"        value="{{ old('kode', $branch->kode) }}"               :error="$errors->first('kode')" />
                <x-input label="Nama Cabang"  name="nama_cabang" value="{{ old('nama_cabang', $branch->nama_cabang) }}"  :error="$errors->first('nama_cabang')" />
                <x-input label="Kota"         name="kota"        value="{{ old('kota', $branch->kota) }}"               :error="$errors->first('kota')" />

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('alamat', $branch->alamat) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <x-button type="submit" variant="primary">Update</x-button>
                    <x-button href="{{ route('branches.index') }}" variant="secondary">Batal</x-button>
                </div>
            </form>
        </x-card>
    </div>

</x-layouts.app>