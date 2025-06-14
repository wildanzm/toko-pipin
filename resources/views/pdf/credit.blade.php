<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $reportTitle }}</title>
    <style>
        @page {
            margin: 25px 35px;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 8px;
            line-height: 1.3;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        h1 {
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

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        table.main-table th,
        table.main-table td {
            border: 1px solid #cccccc;
            padding: 5px;
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

        .summary-section {
            margin-top: 20px;
        }

        table.summary-table {
            width: 350px;
            margin-left: auto;
            margin-right: 0;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.summary-table td {
            padding: 6px 8px;
            border: 1px solid #cccccc;
        }

        table.summary-table td.label {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        table.summary-table td.value {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{!! mb_convert_encoding($reportTitle, 'HTML-ENTITIES', 'UTF-8') !!}</h1>
        <p style="margin:0; font-size:11px;">Gadget Official</p>
    </div>
    <div class="report-info">
        Dicetak pada: {{ $reportDate }}
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width:3%;">No.</th>
                <th style="width:7%;">Tanggal</th>
                <th style="width:9%;">Kode Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Nama Barang</th>
                <th class="text-center" style="width:4%;">Jml</th>
                <th class="text-right" style="width:10%;">Harga Satuan</th>
                <th class="text-right" style="width:10%;">Total Harga</th>
                <th class="text-center" style="width:4%;">Bunga</th>
                <th class="text-center" style="width:6%;">Tenor</th>
                <th class="text-right" style="width:10%;">Total Cicilan</th>
                <th class="text-right" style="width:10%;">Telah Dibayar</th>
                <th class="text-right" style="width:10%;">Sisa Piutang</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalCredit = 0;
                $grandTotalPaid = 0;
                $grandTotalReceivable = 0;
            @endphp
            @forelse ($orders as $index => $order)
                @foreach ($order->items as $item)
                    <tr>
                        @if ($loop->first)
                            <td class="text-center" rowspan="{{ $order->items->count() }}">{{ $index + 1 }}</td>
                            <td class="text-center" rowspan="{{ $order->items->count() }}">
                                {{ $order->created_at->format('d/m/Y') }}</td>
                            <td rowspan="{{ $order->items->count() }}">{{ $order->order_code }}</td>
                            <td rowspan="{{ $order->items->count() }}">{!! mb_convert_encoding(optional($order->user)->name ?? 'N/A', 'HTML-ENTITIES', 'UTF-8') !!}</td>
                        @endif

                        <td>{!! mb_convert_encoding(optional($item->product)->name ?? 'Produk Dihapus', 'HTML-ENTITIES', 'UTF-8') !!}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right whitespace-nowrap">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right whitespace-nowrap">
                            Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>

                        @if ($loop->first)
                            @php
                                $totalPaid = $order->installments->where('is_paid', true)->sum('amount');
                                $receivable = $order->total_amount - $totalPaid;
                                $grandTotalCredit += $order->total_amount;
                                $grandTotalPaid += $totalPaid;
                                $grandTotalReceivable += $receivable;
                            @endphp
                            <td class="text-center" rowspan="{{ $order->items->count() }}">5%</td>
                            <td class="text-center" rowspan="{{ $order->items->count() }}">
                                {{ $order->installment_plan }}</td>
                            <td class="text-right whitespace-nowrap" rowspan="{{ $order->items->count() }}">
                                Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="text-right whitespace-nowrap" rowspan="{{ $order->items->count() }}">
                                Rp{{ number_format($totalPaid, 0, ',', '.') }}</td>
                            <td class="text-right whitespace-nowrap" rowspan="{{ $order->items->count() }}">
                                Rp{{ number_format($receivable, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="13" class="text-center">Tidak ada data penjualan kredit untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($orders->isNotEmpty())
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="label">Total Penjualan Kredit</td>
                    <td class="value">Rp{{ number_format($grandTotalCredit, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Total Piutang Telah Dibayar</td>
                    <td class="value">Rp{{ number_format($grandTotalPaid, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Total Sisa Piutang</td>
                    <td class="value">Rp{{ number_format($grandTotalReceivable, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    @endif

</body>

</html>
