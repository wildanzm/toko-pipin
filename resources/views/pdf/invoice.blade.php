<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice Pembayaran - {{ htmlspecialchars($order_code, ENT_QUOTES, 'UTF-8') }}</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .invoice-container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 25px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        /* ... (CSS lainnya tetap sama seperti yang Anda berikan sebelumnya) ... */
        .header-section {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .header-section .store-name {
            font-size: 24px;
            font-weight: bold;
            color: #0D5CD7;
            margin-bottom: 5px;
        }

        .header-section .invoice-title {
            font-size: 18px;
            margin-top: 5px;
            text-transform: uppercase;
            font-weight: bold;
            color: #555;
        }

        table.order-details {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table.order-details td {
            padding: 5px 2px;
            vertical-align: top;
        }

        table.order-details td.label {
            font-weight: bold;
            width: 130px;
            color: #444;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        table.items-table th,
        table.items-table td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: left;
        }

        table.items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            color: #333;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .summary-section {
            margin-top: 25px;
        }

        table.summary-table {
            width: 45%;
            margin-left: 55%;
            border-collapse: collapse;
        }

        table.summary-table td {
            padding: 8px;
            border: 1px solid #cccccc;
        }

        table.summary-table td.label {
            font-weight: bold;
            background-color: #f0f0f0;
            color: #333;
        }

        table.summary-table td.value {
            text-align: right;
            font-weight: bold;
        }

        table.summary-table tr.grand-total td {
            font-size: 14px;
            color: #0D5CD7;
        }

        .payment-status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #28a745;
            color: #fff;
        }

        .status-pending {
            background-color: #dc3545;
            color: #fff;
        }

        /* Merah untuk Belum Dibayar */
        .status-unpaid-red {
            background-color: #dc3545;
            color: #fff;
        }

        /* Merah untuk Cicilan Belum Lunas */
        .status-other {
            background-color: #6c757d;
            color: #fff;
        }

        .footer-notes {
            margin-top: 35px;
            text-align: center;
            font-size: 10px;
            color: #777777;
            padding-top: 15px;
            border-top: 1px dashed #cccccc;
        }

        .section-title {
            font-size: 14px;
            margin-bottom: 8px;
            color: #0D5CD7;
            font-weight: bold;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header-section">
            <div class="store-name">{{ htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') }}</div>
            <div class="invoice-title">Invoice Pembayaran</div>
        </div>

        <table class="order-details">
            <tr>
                <td class="label">No. Invoice</td>
                <td>: {{ htmlspecialchars($order_code, ENT_QUOTES, 'UTF-8') }}</td>
                <td class="label" style="padding-left: 20px;">Nama Pembeli</td>
                <td>: {{ htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Transaksi</td>
                <td>: {{ $created_at_formatted }}</td>
                <td class="label" style="padding-left: 20px;">Metode Pembayaran</td>
                <td>:
                    @if (strtolower($payment_method) == 'cash')
                        Tunai / Transfer Bank
                    @elseif (strtolower($payment_method) == 'installment')
                        Cicilan @if ($installment_plan)
                            ({{ htmlspecialchars($installment_plan, ENT_QUOTES, 'UTF-8') }})
                        @endif
                    @else
                        {{ htmlspecialchars(Str::ucfirst($payment_method), ENT_QUOTES, 'UTF-8') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Status Pembayaran</td>
                <td colspan="3">:
                    @php
                        // Daftar status yang dianggap "LUNAS"
                        $lunasStatuses = [
                            'paid',
                            'processing',
                            'shipped',
                            'delivered',
                            'completed',
                            'awaiting_return',
                            'returned',
                        ];

                        // Logika sederhana: jika status ada di daftar lunas, maka LUNAS. Jika tidak, maka BELUM LUNAS.
                        if (in_array(strtolower($status), $lunasStatuses)) {
                            $statusTextCleaned = 'LUNAS';
                            $statusClass = 'status-paid'; // Class untuk warna hijau
                        } else {
                            $statusTextCleaned = 'BELUM LUNAS';
                            $statusClass = 'status-pending'; // Class untuk warna merah
                        }
                    @endphp
                    <span class="payment-status-badge {{ $statusClass }}">{{ $statusTextCleaned }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">Rincian Produk</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:5%;">No.</th>
                    <th>Nama Produk</th>
                    <th class="text-center" style="width:10%;">Jumlah</th>
                    <th class="text-right" style="width:25%;">Harga Satuan</th>
                    <th class="text-right" style="width:25%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ htmlspecialchars($item->product_name, ENT_QUOTES, 'UTF-8') }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal Produk</td>
                    <td class="value">Rp {{ number_format($sub_total, 0, ',', '.') }}</td>
                </tr>
                {{-- Menampilkan Biaya Cicilan jika metode pembayaran adalah cicilan dan ada biaya bunga --}}
                @if (strtolower($payment_method) == 'installment' && isset($interest_amount) && $interest_amount > 0)
                    <tr>
                        <td class="label">Bunga (5%)</td> {{-- Label diperbarui --}}
                        <td class="value">Rp {{ number_format($interest_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                {{-- Biaya lain seperti pengiriman, asuransi, PPN tidak ditampilkan di sini sesuai permintaan sebelumnya --}}
                <tr class="grand-total">
                    <td class="label">GRAND TOTAL</td>
                    <td class="value">Rp {{ number_format($total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        @if ($payment_method == 'installment' && !empty($installments_data))
            <div class="section-title" style="margin-top: 25px;">Rincian Tagihan Cicilan</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="text-center">Cicilan Ke-</th>
                        <th class="text-center">Jatuh Tempo</th>
                        <th class="text-right">Jumlah</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($installments_data as $installment)
                        <tr>
                            <td class="text-center">{{ $installment->iteration }}</td>
                            <td class="text-center">{{ $installment->due_date_formatted }}</td>
                            <td class="text-right">Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if ($installment->is_paid)
                                    <span class="payment-status-badge status-paid">Lunas</span>
                                @else
                                    <span class="payment-status-badge status-unpaid-red">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer-notes">
            <p>Terima kasih telah berbelanja di
                <strong>{{ htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') }}</strong>.
            </p>
            <p>Invoice ini merupakan bukti pembayaran yang sah dan diproses secara otomatis oleh sistem.</p>
        </div>
    </div>
</body>

</html>
