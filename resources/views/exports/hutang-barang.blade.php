<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hutang Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Hutang Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Toko</th>
                <th>Jenis Pembayaran</th>
                <th>Status</th>
                <th>Nominal Hutang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hutangBarangs as $index => $barang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->toko }}</td>
                    <td>{{ ucfirst($barang->jenis_pembayaran) }}</td>
                    <td>{{ ucfirst($barang->status) }}</td>
                    <td>Rp {{ number_format($barang->hutang, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
