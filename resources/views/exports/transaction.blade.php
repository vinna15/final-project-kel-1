<table>
    {{-- Judul --}}
    <tr>
        <td colspan="6"><strong>Laporan Transaksi — Minimarket Pak Jayusman</strong></td>
    </tr>
    <tr>
        <td colspan="6">Dicetak: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>

    {{-- Header Kolom --}}
    <tr>
        <td><strong>No</strong></td>
        <td><strong>Kode Transaksi</strong></td>
        <td><strong>Cabang</strong></td>
        <td><strong>Kasir</strong></td>
        <td><strong>Tanggal</strong></td>
        <td><strong>Total</strong></td>
    </tr>

    {{-- Data --}}
    @foreach($transactions as $i => $trx)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $trx->kode_transaksi }}</td>
            <td>{{ $trx->branch->nama_cabang }}</td>
            <td>{{ $trx->kasir->name }}</td>
            <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
            <td>{{ $trx->total }}</td>
        </tr>
    @endforeach

    {{-- Total --}}
    <tr>
        <td colspan="5"><strong>Grand Total</strong></td>
        <td><strong>{{ $total }}</strong></td>
    </tr>
</table>