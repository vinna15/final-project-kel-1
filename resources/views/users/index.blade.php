<x-layouts.app title="Manajemen User">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar User</h2>
        <x-button href="{{ route('users.create') }}" variant="primary">+ Tambah User</x-button>
    </div>

    <x-card>
        <x-table :headers="['Nama', 'Email', 'Role', 'Cabang', 'Aksi']">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600 text-sm">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @foreach($user->roles as $role)
                            <x-badge color="{{ match($role->name) {
                                'owner'      => 'indigo',
                                'manager'    => 'blue',
                                'supervisor' => 'green',
                                'kasir'      => 'yellow',
                                'gudang'     => 'gray',
                                default      => 'gray',
                            } }}">
                                {{ ucfirst($role->name) }}
                            </x-badge>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-sm">
                        {{ $user->branch?->nama_cabang ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <x-button href="{{ route('users.edit', $user) }}" variant="warning" size="sm">
                                Edit
                            </x-button>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" size="sm">Hapus</x-button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada user.</td>
                </tr>
            @endforelse
        </x-table>
        <div class="mt-4">{{ $users->links() }}</div>
    </x-card>

</x-layouts.app>