<x-layouts.app title="Tambah User">
<div class="max-w-xl">
    <x-card title="Form Tambah User">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
            @csrf

            <x-input label="Nama Lengkap" name="name"  type="text"     value="{{ old('name') }}"  :error="$errors->first('name')" />
            <x-input label="Email"        name="email" type="email"    value="{{ old('email') }}" :error="$errors->first('email')" />
            <x-input label="Password"     name="password"              type="password"            :error="$errors->first('password')" />
            <x-input label="Konfirmasi Password" name="password_confirmation" type="password" />

            <x-select
                label="Role"
                name="role"
                :error="$errors->first('role')"
                :options="$roles->pluck('name', 'name')->map(fn($r) => ucfirst($r))->toArray()"
                placeholder="Pilih Role"
            />

            <x-select
                label="Cabang"
                name="branch_id"
                :error="$errors->first('branch_id')"
                :options="$branches->pluck('nama_cabang', 'id')->toArray()"
                placeholder="Pilih Cabang (kosongkan untuk Owner)"
            />

            <div class="flex gap-3 pt-2">
                <x-button type="submit" variant="primary">Simpan</x-button>
                <x-button href="{{ route('users.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
</div>
</x-layouts.app>