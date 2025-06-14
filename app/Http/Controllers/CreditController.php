<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class CreditController extends Controller
{
    /**
     * Fungsi untuk memastikan string adalah UTF-8 yang valid, mengabaikan karakter yang tidak valid.
     */
    private function cleanForPdf($string): string
    {
        if (is_null($string)) {
            return '';
        }
        // Mengonversi karakter ke entitas HTML, dengan tetap menjaga encoding UTF-8.
        return mb_convert_encoding((string)$string, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Membuat dan mengunduh laporan penjualan kredit dalam format PDF.
     */
    public function streamCreditReport(Request $request)
    {
        // Ambil parameter filter dari request (yang dikirim dari komponen Livewire)
        $search = $request->input('search');
        $filterStatus = $request->input('status');

        $ordersQuery = Order::with(['user', 'items.product', 'installments'])
            ->where('payment_method', 'installment') // Hanya pesanan dengan metode cicilan
            ->when($search, function ($query, $search) {
                $query->where('order_code', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q_user) use ($search) {
                        $q_user->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->when($filterStatus, function ($query, $status) {
                if ($status === 'pending_installments') {
                    $query->whereHas('installments', fn($q) => $q->where('is_paid', false));
                } elseif ($status === 'fully_paid') {
                    $query->whereDoesntHave('installments', fn($q) => $q->where('is_paid', false));
                }
            })
            ->orderBy('created_at', 'desc');

        $orders = $ordersQuery->get();

        // Hitung denda keterlambatan untuk setiap cicilan
        $orders->transform(function ($order) {
            $order->installments->transform(function ($installment) {
                $installment->late_fee = 0;
                $installment->late_days = 0;
                $dueDate = Carbon::parse($installment->due_date)->startOfDay();
                $today = Carbon::now()->startOfDay();
                if (!$installment->is_paid && $today->gt($dueDate)) {
                    $lateDays = $today->diffInDays($dueDate);
                    if ($lateDays > 0) {
                        $installment->late_days = $lateDays;
                        $installment->late_fee = ($installment->amount * 0.01) * $lateDays; // Denda 1% per hari
                    }
                }
                return $installment;
            });
            return $order;
        });

        // Menyiapkan data untuk dikirim ke view PDF
        $dataForPdf = [
            'orders' => $orders,
            'reportTitle' => 'Laporan Penjualan Kredit',
            'reportDate' => Carbon::now()->locale('id_ID.UTF-8')->translatedFormat('l, d F Y'),
        ];

        // Ganti 'pdf.credit_report_template' dengan path view Anda
        $pdf = Pdf::loadView('pdf.credit', $dataForPdf)
            ->setPaper('a4', 'landscape'); // Mengatur orientasi kertas

        $filename = 'laporan-tagihan-kredit-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }
}
