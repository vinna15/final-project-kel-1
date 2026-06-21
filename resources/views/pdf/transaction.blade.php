<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2937; }

        .header { text-align: center; padding: 16px 0 12px; border-bottom: 2px solid #4f46e5; }
        .header h1 { font-size: 18px; font-weight: bold; color: #4f46e5; }
        .header p  { font-size: 10px; color: #6b7280; margin-top: 2px; }

        .meta { padding: 10px 0; display: flex; justify-content: space-between; font-size: 10px; color: #374151; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr { background: #4f46e5; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f3f4f6; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }

        .total-row td { font-weight: bold; background: #eef2ff; color: #4338ca; }
        .footer { margin-top: 16px; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Minimarket Jayusman</h1>
        <p>Laporan Transaksi Penjualan</p>
        @if($request->dari || $request->sampai)
            <p>Periode: {{ $request->dari ?? '—' }} s/d {{ $request->sampai ?? '—' }}</p>
        @endif
    </div>

    <div class="meta">
        <span>Dicetak: {{ now()->format('d/m/Y H:i') }}</span>
        <span>Total Transaksi: {{ $transactions->count() }}</span>
        <span>Grand Total: Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Cabang</th>
                <th>Kasir</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $i => $trx)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $trx->kode_transaksi }}</td>
                    <td>{{ $trx->branch->nama_cabang }}</td>
                    <td>{{ $trx->kasir->name }}</td>
                    <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align:right; padding-right:10px;">Grand Total</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem — {{ config('app.name') }}
    </div>

</body>
</html>