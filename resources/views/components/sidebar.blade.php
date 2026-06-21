<aside class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 h-full">

    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-slate-700 flex-shrink-0">
        <div class="flex items-center gap-2">
            <x-heroicon-o-shopping-cart class="w-6 h-6 text-teal-400" />
            <h1 class="text-xl font-bold tracking-wide">MiniMarket</h1>
        </div>
        <p class="text-xs text-slate-400 mt-1"> Jayusman</p>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1 sidebar-scroll">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                  {{ request()->routeIs('dashboard')
                      ? 'bg-teal-600 text-white font-semibold'
                      : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <x-heroicon-o-chart-bar class="w-5 h-5 flex-shrink-0" />
            <span>Dashboard</span>
        </a>

        {{-- OWNER --}}
        @role('owner')
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Master Data
            </p>

            @foreach([
                ['route' => 'branches.index', 'icon' => 'building-storefront', 'label' => 'Cabang'],
                ['route' => 'users.index',    'icon' => 'users',               'label' => 'User'],
                ['route' => 'products.index', 'icon' => 'cube',                'label' => 'Barang'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route']))
                              ? 'bg-teal-600 text-white font-semibold'
                              : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Transaksi
            </p>
            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('transactions.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <x-heroicon-o-receipt-percent class="w-5 h-5 flex-shrink-0" />
                <span>Transaksi</span>
            </a>

            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Stok
            </p>
            <a href="{{ route('stocks.history') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('stocks.history') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <x-heroicon-o-clock class="w-5 h-5 flex-shrink-0" />
                <span>Riwayat Stok</span>
            </a>

            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Laporan
            </p>
            <a href="{{ route('reports.transaction') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('reports.transaction') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <x-heroicon-o-document-text class="w-5 h-5 flex-shrink-0" />
                <span>Lap. Transaksi</span>
            </a>
            <a href="{{ route('reports.stock') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('reports.stock') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <x-heroicon-o-chart-bar-square class="w-5 h-5 flex-shrink-0" />
                <span>Lap. Stok</span>
            </a>
        @endrole

        {{-- MANAGER --}}
        @role('manager')
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Operasional
            </p>
            @foreach([
                ['route' => 'products.index',     'icon' => 'cube',                'label' => 'Barang',       'match' => 'products.*'],
                ['route' => 'stocks.index',        'icon' => 'archive-box',         'label' => 'Stok',         'match' => 'stocks.index'],
                ['route' => 'transactions.index',  'icon' => 'receipt-percent',     'label' => 'Transaksi',    'match' => 'transactions.*'],
                ['route' => 'stocks.history',      'icon' => 'clock',               'label' => 'Riwayat Stok', 'match' => 'stocks.history'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['match']) ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Laporan
            </p>
            @foreach([
                ['route' => 'reports.transaction', 'icon' => 'document-text',      'label' => 'Lap. Transaksi'],
                ['route' => 'reports.stock',       'icon' => 'chart-bar-square',   'label' => 'Lap. Stok'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['route']) ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endrole

        {{-- SUPERVISOR --}}
        @role('supervisor')
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Monitoring
            </p>
            @foreach([
                ['route' => 'transactions.index', 'icon' => 'receipt-percent',  'label' => 'Transaksi',    'match' => 'transactions.*'],
                ['route' => 'stocks.index',       'icon' => 'archive-box',      'label' => 'Stok',         'match' => 'stocks.index'],
                ['route' => 'stocks.in',          'icon' => 'arrow-down-tray',  'label' => 'Barang Masuk', 'match' => 'stocks.in'],
                ['route' => 'stocks.out',         'icon' => 'arrow-up-tray',    'label' => 'Barang Keluar','match' => 'stocks.out'],
                ['route' => 'stocks.history',     'icon' => 'clock',            'label' => 'Riwayat Stok', 'match' => 'stocks.history'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['match']) ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Laporan
            </p>
            @foreach([
                ['route' => 'reports.transaction', 'icon' => 'document-text',    'label' => 'Lap. Transaksi'],
                ['route' => 'reports.stock',       'icon' => 'chart-bar-square', 'label' => 'Lap. Stok'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['route']) ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endrole

        {{-- KASIR --}}
        @role('kasir')
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Kasir
            </p>
            @foreach([
                ['route' => 'transactions.create', 'icon' => 'plus-circle',      'label' => 'Transaksi Baru', 'match' => 'transactions.create'],
                ['route' => 'transactions.index',  'icon' => 'receipt-percent',  'label' => 'Riwayat',        'match' => 'transactions.index'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['match']) ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endrole

        {{-- GUDANG --}}
        @role('gudang')
            <p class="text-xs text-slate-500 uppercase tracking-widest mt-4 mb-1 px-2">
                Gudang
            </p>
            @foreach([
                ['route' => 'stocks.index',   'icon' => 'archive-box',     'label' => 'Data Stok',    'match' => 'stocks.index'],
                ['route' => 'stocks.in',      'icon' => 'arrow-down-tray', 'label' => 'Barang Masuk', 'match' => 'stocks.in'],
                ['route' => 'stocks.out',     'icon' => 'arrow-up-tray',   'label' => 'Barang Keluar','match' => 'stocks.out'],
                ['route' => 'stocks.history', 'icon' => 'clock',           'label' => 'Riwayat Stok', 'match' => 'stocks.history'],
            ] as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs($item['match']) ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="w-5 h-5 flex-shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endrole

    </nav>

    {{-- User Info --}}
    <div class="px-4 py-4 border-t border-slate-700 flex-shrink-0">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center
                        text-white text-sm font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">
                    {{ ucfirst(auth()->user()->getRoleNames()->first()) }}
                    @if(auth()->user()->branch)
                        — {{ auth()->user()->branch->nama_cabang }}
                    @endif
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-1 text-xs text-red-400 hover:text-red-300 transition">
                <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                Logout
            </button>
        </form>
    </div>

</aside>

<style>
    .sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(20, 184, 166, 0.3) transparent;
    }
    .sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(20, 184, 166, 0.3);
        border-radius: 999px;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(20, 184, 166, 0.6);
    }
</style>