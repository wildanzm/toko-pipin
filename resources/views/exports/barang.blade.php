<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian Barang</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Pembelian Barang</h2>
    <h3 style="text-align: center;">Toko Pipin</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th>Toko</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Total</th>
                <th>Hutang</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Metode Pembelian</th>
                <th>Status Pembelian</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kodebarang }}</td>
                <td>{{ $barang->nama_toko_suplier }}</td>
                <td>{{ $barang->kuantitas }} {{ $barang->jenis_stok }}</td>
                <td>Rp {{ number_format($barang->harga_per_satu, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($barang->harga_total, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($barang->hutang, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($barang->pembayaran, 0, ',', '.') }}</td>
                <td>{{ ucfirst($barang->status_pembayaran) }}</td>
                <td>{{ ucfirst($barang->jenis_pembayaran ?? '-') }}</td>
                <td>{{ ucfirst($barang->status_pembelian ?? '-') }}</td>
                <td>{{ $barang->created_at ? $barang->created_at->format('d-m-Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
