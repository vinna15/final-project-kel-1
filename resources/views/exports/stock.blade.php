<table>
    <tr>
        <td colspan="7"><strong>Laporan Stok — Minimarket Pak Jayusman</strong></td>
    </tr>
    <tr>
        <td colspan="7">Dicetak: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>

    <tr>
        <td><strong>No</strong></td>
        <td><strong>Tanggal</strong></td>
        <td><strong>Barang</strong></td>
        <td><strong>Jenis</strong></td>
        <td><strong>Jumlah</strong></td>
        <td><strong>Keterangan</strong></td>
        <td><strong>Dicatat Oleh</strong></td>
    </tr>

    @foreach($histories as $i => $h)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $h->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $h->product->nama_barang }}</td>
            <td>{{ ucfirst($h->jenis) }}</td>
            <td>{{ $h->jumlah }}</td>
            <td>{{ $h->keterangan ?? '-' }}</td>
            <td>{{ $h->creator->name }}</td>
        </tr>
    @endforeach
</table>