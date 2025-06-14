<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import Facade PDF
use Illuminate\Support\Str;     // Untuk Str::ucfirst
use Carbon\Carbon;              // Untuk Carbon::parse jika diperlukan

class InvoiceController extends Controller
{
    /**
     * Fungsi untuk membersihkan string agar aman untuk DomPDF.
     */
    private function cleanForPdf($string)
    {
        if (is_null($string)) {
            return '';
        }
        return mb_convert_encoding((string)$string, 'HTML-ENTITIES', 'UTF-8');
    }

    public function streamUserInvoice(Request $request, $orderId)
    {
        $order = Order::with([
            'user',
            'items' => function ($query) {
                $query->with('product');
            },
            'installments'
        ])
            ->where('user_id', Auth::id())
            ->where('id', $orderId)
            ->firstOrFail();

        $locale = config('app.locale_php') ?: 'id_ID.UTF-8';

        // Hitung subtotal dari item jika belum ada di order (atau untuk verifikasi)
        $subTotalForCalc = $order->sub_total ?? $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $interestAmountForCalc = 0;
        if (strtolower($order->payment_method) == 'installment' && !empty($order->installment_plan)) {
            // Hitung bunga 5% dari subtotal produk
            $interestAmountForCalc = $subTotalForCalc * 0.05;
        }

        // Grand total yang seharusnya sudah termasuk bunga jika ada di $order->total_amount
        // Jika $order->total_amount belum termasuk bunga, Anda bisa hitung di sini:
        // $grandTotalForCalc = $subTotalForCalc + $interestAmountForCalc;
        // Namun, idealnya $order->total_amount sudah final.

        $dataForPdf = [
            'order_code' => $this->cleanForPdf($order->order_code),
            'created_at_formatted' => Carbon::parse($order->created_at)->locale($locale)->translatedFormat('d F Y'),
            'status' => $this->cleanForPdf($order->status),
            'payment_method' => $this->cleanForPdf($order->payment_method),
            'installment_plan' => $this->cleanForPdf($order->installment_plan),
            'user_name' => $this->cleanForPdf(optional($order->user)->name),

            'items' => $order->items->map(function ($item) {
                return (object)[
                    'product_name' => $this->cleanForPdf(optional($item->product)->name ?? 'Produk Tidak Tersedia'),
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ];
            })->all(),

            'installments_data' => $order->installments->map(function ($installment, $index) use ($locale) {
                return (object)[
                    'due_date_formatted' => Carbon::parse(optional($installment)->due_date)->locale($locale)->translatedFormat('d F Y'),
                    'amount' => optional($installment)->amount,
                    'is_paid' => optional($installment)->is_paid,
                    'iteration' => $index + 1,
                ];
            })->all(),

            'sub_total' => $subTotalForCalc, // Subtotal produk sebelum bunga
            'interest_amount' => $interestAmountForCalc, // Bunga 5% jika cicilan
            'total_amount' => $order->total_amount, // Grand total dari order (seharusnya sudah termasuk bunga jika cicilan)
            'storeName' => $this->cleanForPdf('Gadget Official'),
        ];

        $pdf = Pdf::loadView('pdf.invoice', $dataForPdf);

        $filename = 'invoice-pembayaran-' . $dataForPdf['order_code'] . '.pdf';

        return $pdf->stream($filename);
    }
}
