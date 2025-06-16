<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Retur Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Retur Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Nama Toko</th>
                <th>Kuantitas</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Jenis Pembayaran</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returBarangs as $index => $barang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->nama_toko_suplier }}</td>
                    <td>{{ $barang->kuantitas }}</td>
                    <td>{{ number_format($barang->harga_per_satu, 0, ',', '.') }}</td>
                    <td>{{ number_format($barang->harga_total, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($barang->jenis_pembayaran) }}</td>
                    <td>{{ \Carbon\Carbon::parse($barang->tanggal_transaksi)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
