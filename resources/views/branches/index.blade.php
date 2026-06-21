<x-layouts.app title="Manajemen Cabang">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Daftar Cabang</h2>
            <p class="text-sm text-gray-500">Total {{ $branches->total() }} cabang terdaftar</p>
        </div>
        <x-button href="{{ route('branches.create') }}" variant="primary">
            + Tambah Cabang
        </x-button>
    </div>

    <x-card>
        <x-table :headers="['Kode', 'Nama Cabang', 'Kota', 'Alamat', 'Jumlah User', 'Aksi']">
            @forelse($branches as $branch)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono text-indigo-700">{{ $branch->kode }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $branch->nama_cabang }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $branch->kota }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $branch->alamat }}</td>
                    <td class="px-4 py-3">
                        <x-badge color="indigo">{{ $branch->users_count }} user</x-badge>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <x-button href="{{ route('branches.edit', $branch) }}" variant="warning" size="sm">
                                Edit
                            </x-button>

                            <form method="POST" action="{{ route('branches.destroy', $branch) }}"
                                  onsubmit="return confirm('Hapus cabang {{ $branch->nama_cabang }}?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger" size="sm">Hapus</x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Belum ada cabang.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $branches->links() }}
        </div>
    </x-card>

</x-layouts.app>