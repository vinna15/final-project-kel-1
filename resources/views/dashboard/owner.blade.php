<x-layouts.app title="Dashboard Owner">

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <x-card class="text-center">
            <p class="text-3xl font-bold text-teal-600">{{ $totalCabang }}</p>
            <p class="text-sm text-slate-500 mt-1">Total Cabang</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $totalUser }}</p>
            <p class="text-sm text-slate-500 mt-1">Total User</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-3xl font-bold text-green-600">{{ $totalBarang }}</p>
            <p class="text-sm text-slate-500 mt-1">Total Barang</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-3xl font-bold text-amber-600">{{ $totalTransaksi }}</p>
            <p class="text-sm text-slate-500 mt-1">Total Transaksi</p>
        </x-card>
        <x-card class="text-center">
            <p class="text-xl font-bold text-red-600">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
            <p class="text-sm text-slate-500 mt-1">Total Penjualan</p>
        </x-card>
    </div>

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-600">
            Grafik Penjualan —
            <span class="text-teal-600">{{ $label }}</span>
        </h3>
        <div class="flex gap-2">
            <a href="{{ route('dashboard', ['period' => '7days']) }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition
                      {{ $period === '7days'
                          ? 'bg-teal-600 text-white'
                          : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                7 Hari
            </a>
            <a href="{{ route('dashboard', ['period' => '30days']) }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition
                      {{ $period === '30days'
                          ? 'bg-teal-600 text-white'
                          : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                30 Hari
            </a>
            <a href="{{ route('dashboard', ['period' => 'monthly']) }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg transition
                      {{ $period === 'monthly'
                          ? 'bg-teal-600 text-white'
                          : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                Per Bulan
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <x-card title="Penjualan">
            <canvas id="barChart" height="120"></canvas>
        </x-card>
        <x-card title="Transaksi per Cabang">
            <canvas id="pieChart" height="120"></canvas>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Tren Penjualan">
            <canvas id="lineChart" height="120"></canvas>
        </x-card>
        <x-card title="5 Produk Terlaris">
            <x-table :headers="['Produk', 'Total Terjual']">
                @forelse($topProducts as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm font-medium text-slate-800">
                            {{ $item->product->nama_barang ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <x-badge color="teal">{{ $item->total_qty }} pcs</x-badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-6 text-center text-slate-400 text-sm">
                            Belum ada data transaksi.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>

    @push('scripts')
    <script>
        const period   = "{{ $period }}";
        const rawData  = @json($salesData);
        const labels   = [];
        const salesArr = [];

        if (period === 'monthly') {
            const bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            for (let i = 1; i <= 12; i++) {
                labels.push(bulanNames[i - 1]);
                const found = rawData.find(d => parseInt(d.periode) === i);
                salesArr.push(found ? parseFloat(found.total) : 0);
            }
        } else {
            rawData.forEach(d => {
                const date = new Date(d.periode);
                labels.push(date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
                salesArr.push(parseFloat(d.total));
            });
        }

        const branchLabels = @json($salesPerBranch->map(fn($b) => $b->branch->nama_cabang ?? 'N/A'));
        const branchData   = @json($salesPerBranch->pluck('jumlah_transaksi'));

        const tealColors = [
            'rgba(20,184,166,0.8)',
            'rgba(59,130,246,0.8)',
            'rgba(16,185,129,0.8)',
            'rgba(245,158,11,0.8)',
            'rgba(239,68,68,0.8)',
        ];

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: salesArr,
                    backgroundColor: 'rgba(20, 184, 166, 0.7)',
                    borderColor: 'rgba(20, 184, 166, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }
                    }
                }
            }
        });

        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: branchLabels,
                datasets: [{ data: branchData, backgroundColor: tealColors }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Tren Penjualan',
                    data: salesArr,
                    borderColor: 'rgba(20, 184, 166, 1)',
                    backgroundColor: 'rgba(20, 184, 166, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgba(20, 184, 166, 1)',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }
                    }
                }
            }
        });
    </script>
    @endpush

</x-layouts.app>