<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{!! $reportTitle !!}</title>
    <style>
        /* ... (CSS Anda tetap sama persis seperti sebelumnya, tidak ada perubahan di sini) ... */
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
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #0D5CD7;
        }

        .report-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        table.sales-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        table.sales-table th,
        table.sales-table td {
            border: 1px solid #cccccc;
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
        }

        table.sales-table th {
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

        table.sales-table tfoot th,
        table.sales-table tfoot td {
            font-weight: bold;
            background-color: #f2f2f2;
            border-top: 2px solid #aaa;
            font-size: 11px;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-top: 25px;
            position: fixed;
            bottom: 0px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="header">
            <h1>Laporan Penjualan</h1>
            <p style="margin:0; font-size:11px;">Gadget Official</p>
        </div>
        <div class="report-info">
            <div>{!! mb_convert_encoding($reportPeriodInfo, 'HTML-ENTITIES', 'UTF-8') !!}</div>
            <div style="font-size: 9px; color: #777;">Dicetak pada: {{ $reportDate }}</div>
        </div>

        <table class="sales-table">
            <thead>
                <tr>
                    <th style="width:3%;">No.</th>
                    <th style="width:13%;">Tanggal</th>
                    <th style="width:12%;">Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Barang</th>
                    <th class="text-center" style="width:5%;">Jumlah</th>
                    <th class="text-right" style="width:15%;">Harga Satuan</th>
                    <th class="text-right" style="width:15%;">Total Harga</th>
                    <th style="width:10%;">Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salesItems as $index => $item)
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
                        <td>
                            @if (strtolower(optional($item->order)->payment_method) == 'cash')
                                Tunai
                            @elseif (strtolower(optional($item->order)->payment_method) == 'installment')
                                Cicilan
                            @else
                                {!! mb_convert_encoding(Str::ucfirst(optional($item->order)->payment_method), 'HTML-ENTITIES', 'UTF-8') !!}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data penjualan untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>

            {{-- PERBAIKAN TFOOT --}}
            @if ($salesItems->isNotEmpty())
                <tfoot class="total-summary">
                    <tr>
                        {{-- Gabungkan 7 kolom pertama menjadi satu sel --}}
                        <th colspan="7" class="text-left">TOTAL PENJUALAN:</th>

                        {{-- Total Penjualan berada di kolom ke-8 (kolom "Total Harga") --}}
                        <td class="text-right">Rp{{ number_format($totalSalesRevenue, 0, ',', '.') }}
                        </td>
                        <td class="text-right">
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>

    </div>
</body>

</html>
