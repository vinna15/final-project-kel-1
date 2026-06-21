<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2937; }

        .header { text-align: center; padding: 16px 0 12px; border-bottom: 2px solid #059669; }
        .header h1 { font-size: 18px; font-weight: bold; color: #059669; }
        .header p  { font-size: 10px; color: #6b7280; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        thead tr { background: #059669; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f0fdf4; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #d1fae5; font-size: 10px; }

        .masuk  { color: #059669; font-weight: bold; }
        .keluar { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 16px; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Minimarket Jayusman</h1>
        <p>Laporan Riwayat Stok</p>
        @if($request->dari || $request->sampai)
            <p>Periode: {{ $request->dari ?? '—' }} s/d {{ $request->sampai ?? '—' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $i => $h)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $h->product->nama_barang }}</td>
                    <td class="{{ $h->jenis }}">{{ ucfirst($h->jenis) }}</td>
                    <td>{{ $h->jumlah }}</td>
                    <td>{{ $h->keterangan ?? '—' }}</td>
                    <td>{{ $h->creator->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem — {{ config('app.name') }}
    </div>

</body>
</html>