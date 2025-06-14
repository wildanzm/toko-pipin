<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .page-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #0D5CD7;
        }

        .report-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #cccccc;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
        }

        table.main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        table.main-table tfoot th,
        table.main-table tfoot td {
            font-weight: bold;
            background-color: #f2f2f2;
            border-top: 2px solid #aaa;
            font-size: 10px;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-top: 25px;
            position: fixed;
            bottom: 10px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="header">
            <h1>{!! mb_convert_encoding($reportTitle, 'HTML-ENTITIES', 'UTF-8') !!}</h1>
            <p style="margin:0; font-size:12px;">Gadget Official</p>
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th style="width:3%;">No.</th>
                    <th style="width:12%;">Tanggal Transaksi</th>
                    <th style="width:12%;">Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Barang Diretur</th>
                    <th class="text-center" style="width:6%;">Jumlah Retur</th>
                    <th class="text-right" style="width:15%;">Harga Satuan</th>
                    <th class="text-right" style="width:15%;">Total Nilai Retur</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($returnedItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ optional($item->order)->created_at->format('d/m/Y') }}</td>
                        <td>{{ optional($item->order)->order_code }}</td>
                        <td>{!! mb_convert_encoding(optional($item->order->user)->name ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') !!}</td>
                        <td>{!! mb_convert_encoding(optional($item->product)->name ?? 'Produk Dihapus', 'HTML-ENTITIES', 'UTF-8') !!}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right whitespace-nowrap">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right whitespace-nowrap">
                            Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data pengembalian untuk dilaporkan.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($returnedItems->isNotEmpty())
                <tfoot>
                    <tr class="total-summary">
                        <th colspan="5" class="text-right">TOTAL KESELURUHAN:</th>
                        <td class="text-center">{{ number_format($totalReturnQuantity, 0, ',', '.') }} unit</td>
                        <td class="text-right" colspan="2">Rp{{ number_format($totalReturnAmount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</body>

</html>
