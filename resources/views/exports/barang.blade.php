<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Barang Masuk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th>Toko</th>
                <th>Harga Beli</th>
                <th>Stok</th>
                <th>Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kodebarang }}</td>
                <td>{{ $barang->nama_toko_suplier }}</td>
                <td>Rp {{ number_format($barang->harga_per_satu, 0, ',', '.') }}</td>
                <td>{{ $barang->kuantitas }} {{ $barang->jenis_stok }}</td>
                <td>Rp {{ number_format($barang->pembayaran, 0, ',', '.') }}</td>
                <td>{{ ucfirst($barang->status_pembayaran) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
