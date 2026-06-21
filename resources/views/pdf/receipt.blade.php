<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; width: 80mm; }

        .center { text-align: center; }
        .bold   { font-weight: bold; }
        .line   { border-top: 1px dashed #374151; margin: 6px 0; }

        .header { padding: 8px 0; text-align: center; }
        .header h1 { font-size: 13px; font-weight: bold; }

        table { width: 100%; margin-top: 6px; }
        td { padding: 3px 0; vertical-align: top; }
        .item-name { width: 55%; }
        .item-qty  { width: 15%; text-align: center; }
        .item-sub  { width: 30%; text-align: right; }

        .total-section { margin-top: 8px; }
        .total-row { display: flex; justify-content: space-between; padding: 2px 0; }
        .grand-total { font-weight: bold; font-size: 12px; border-top: 1px solid #000; padding-top: 4px; margin-top: 4px; }

        .footer { text-align: center; margin-top: 10px; font-size: 9px; color: #6b7280; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $transaction->branch->nama_cabang }}</h1>
        <p>{{ $transaction->branch->alamat }}</p>
        <p>{{ $transaction->branch->kota }}</p>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>No. Transaksi</td>
            <td style="text-align:right">{{ $transaction->kode_transaksi }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td style="text-align:right">{{ $transaction->tanggal->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td style="text-align:right">{{ $transaction->kasir->name }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th class="item-name" style="text-align:left">Barang</th>
                <th class="item-qty">Qty</th>
                <th class="item-sub">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $detail)
                <tr>
                    <td class="item-name">{{ $detail->product->nama_barang }}</td>
                    <td class="item-qty">{{ $detail->qty }}</td>
                    <td class="item-sub">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="color:#6b7280; font-size:9px; padding-bottom:3px;">
                        Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} × {{ $detail->qty }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="bold">TOTAL</td>
            <td style="text-align:right; font-weight:bold; font-size:13px;">
                Rp {{ number_format($transaction->total, 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
    </div>

</body>
</html>