<x-layouts.app title="Detail Transaksi">

    <div class="mb-4 flex items-center justify-between">
        <x-button href="{{ route('transactions.index') }}" variant="secondary" size="sm">
            ← Kembali
        </x-button>

        @role('kasir|owner')
            <x-button href="{{ route('pdf.receipt', $transaction) }}" variant="danger" size="sm">
                <x-heroicon-o-printer class="w-4 h-4" />
                Cetak Struk PDF
            </x-button>
        @endrole
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info Transaksi --}}
        <div class="lg:col-span-1">
            <x-card title="Info Transaksi">
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">
                            Kode Transaksi
                        </dt>
                        <dd class="font-mono text-indigo-700 font-bold text-base mt-0.5">
                            {{ $transaction->kode_transaksi }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Cabang</dt>
                        <dd class="font-medium text-gray-800 mt-0.5">
                            {{ $transaction->branch->nama_cabang }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Kasir</dt>
                        <dd class="text-gray-700 mt-0.5">{{ $transaction->kasir->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Tanggal</dt>
                        <dd class="text-gray-700 mt-0.5">
                            {{ $transaction->tanggal->format('d F Y') }}
                        </dd>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <dt class="text-gray-500 text-xs uppercase tracking-wide">Total</dt>
                        <dd class="font-bold text-green-600 text-xl mt-0.5">
                            Rp {{ number_format($transaction->total, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </x-card>
        </div>

        {{-- Detail Item --}}
        <div class="lg:col-span-2">
            <x-card title="Detail Item">
                <x-table :headers="['Barang', 'Harga Satuan', 'Qty', 'Subtotal']">
                    @foreach ($transaction->details as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $detail->product->nama_barang }}
                                <span class="block text-xs text-gray-400 font-normal">
                                    {{ $detail->product->kode_barang }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <x-badge color="indigo">{{ $detail->qty }}</x-badge>
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </x-table>

                {{-- Grand Total --}}
                <div
                    class="mt-4 pt-4 border-t border-gray-200
                            flex justify-between items-center">
                    <span class="font-semibold text-gray-700">Grand Total</span>
                    <span class="font-bold text-green-600 text-xl">
                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                    </span>
                </div>
            </x-card>
        </div>

    </div>

</x-layouts.app>
