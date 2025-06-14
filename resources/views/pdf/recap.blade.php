<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .page-container {
            width: 95%;
            /* Atau sesuaikan dengan lebar yang Anda inginkan, misal 80% */
            margin-left: auto;
            margin-right: auto;
            padding: 20px 0;
            /* Menambahkan padding vertikal jika perlu */
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
            font-size: 20px;
            color: #0D5CD7;
        }

        .report-info {
            text-align: center;
            margin-bottom: 25px;
            font-size: 12px;
            color: #555;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: left;
        }

        table.main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.main-table tfoot td {
            font-weight: bold;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-red {
            color: #dc3545;
        }

        tr.total-row td {
            background-color: #e9ecef;
            font-weight: bold;
            border-top: 2px solid #aaa;
        }

        tr.grand-total-row td {
            background-color: #dee2e6;
            font-weight: bold;
            font-size: 12px;
        }

        tr.section-header td {
            background-color: #f8f9fa;
            font-weight: bold;
            border-top: 4px double #ccc;
            padding-top: 12px;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="header">
            <h1>{{ $reportTitle }}</h1>
            <p style="margin:0; font-size:12px;">Gadget Official</p>
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Penjualan Tunai</td>
                    <td class="text-center">{{ $cashSalesCount }}</td>
                    <td class="text-right">Rp{{ number_format($cashSalesTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Penjualan Kredit</td>
                    <td class="text-center">{{ $creditSalesCount }}</td>
                    <td class="text-right">Rp{{ number_format($creditSalesTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Retur Penjualan</td>
                    <td class="text-center">{{ $returnCount }}</td>
                    <td class="text-right">(Rp{{ number_format($returnTotal, 0, ',', '.') }})</td>
                </tr>
                <tr>
                    <td>Total Penjualan Kotor</td>
                    <td class="text-center">{{ $grossSalesCount }}</td>
                    <td class="text-right">Rp{{ number_format($grossSalesTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Retur</td>
                    <td class="text-center">{{ $returnCount }}</td>
                    <td class="text-right">(Rp{{ number_format($returnTotal, 0, ',', '.') }})</td>
                </tr>
                <tr>
                    <td>Total Penjualan Bersih</td>
                    <td class="text-center">-</td>
                    <td class="text-right">Rp{{ number_format($netSalesTotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Piutang Kredit</td>
                    <td class="text-center">{{ $totalReceivablesCount }}</td>
                    <td class="text-right">Rp{{ number_format($totalReceivablesAmount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Sudah Dibayar</td>
                    <td class="text-center">-</td>
                    <td class="text-right">Rp{{ number_format($totalPaidReceivables, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Sisa Piutang</td>
                    <td class="text-center">-</td>
                    <td class="text-right">Rp{{ number_format($remainingReceivables, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

    </div>
</body>

</html>
