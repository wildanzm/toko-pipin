<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Installment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RecapController extends Controller
{
    /**
     * Membuat dan mengunduh laporan rekapitulasi dalam format PDF.
     */
    public function StreamRecap(Request $request)
    {
        // Kumpulkan semua data yang diperlukan
        $validSaleStatuses = ['paid', 'processing', 'shipped', 'delivered', 'completed'];

        // Penjualan Tunai
        $cashSales = Order::where('payment_method', 'cash')->whereIn('status', $validSaleStatuses)->get();
        $cashSalesCount = $cashSales->count();
        $cashSalesTotal = $cashSales->sum('total_amount');

        // Penjualan Kredit
        $creditSales = Order::where('payment_method', 'installment')->whereIn('status', $validSaleStatuses)->get();
        $creditSalesCount = $creditSales->count();
        $creditSalesTotal = $creditSales->sum('total_amount');

        // Retur Penjualan
        $returnedOrders = Order::whereIn('status', ['awaiting_return', 'returned'])->get();
        $returnCount = $returnedOrders->count();
        $returnTotal = $returnedOrders->reduce(function ($carry, $order) {
            return $carry + $order->items->sum(fn($item) => $item->price * $item->quantity);
        }, 0);

        // Total Penjualan Kotor
        $grossSalesTotal = $cashSalesTotal + $creditSalesTotal;

        // Total Penjualan Bersih
        $netSalesTotal = $grossSalesTotal - $returnTotal;

        // Piutang
        $totalPaidReceivables = Installment::where('is_paid', true)
            ->whereHas('order', function ($query) use ($validSaleStatuses) {
                $query->whereIn('status', array_merge($validSaleStatuses, ['awaiting_return', 'returned']));
            })
            ->sum('amount');
        $remainingReceivables = $creditSalesTotal - $totalPaidReceivables;

        // Menyiapkan data untuk dikirim ke view PDF
        $dataForPdf = [
            'reportTitle' => 'Rekapitulasi',
            'reportDate' => Carbon::now()->locale('id_ID.UTF-8')->translatedFormat('l, d F Y'),
            'cashSalesCount' => $cashSalesCount,
            'cashSalesTotal' => $cashSalesTotal,
            'creditSalesCount' => $creditSalesCount,
            'creditSalesTotal' => $creditSalesTotal,
            'returnCount' => $returnCount,
            'returnTotal' => $returnTotal,
            'grossSalesCount' => $cashSalesCount + $creditSalesCount,
            'grossSalesTotal' => $grossSalesTotal,
            'netSalesTotal' => $netSalesTotal,
            'totalReceivablesCount' => $creditSalesCount,
            'totalReceivablesAmount' => $creditSalesTotal,
            'totalPaidReceivables' => $totalPaidReceivables,
            'remainingReceivables' => $remainingReceivables,
        ];

        $pdf = Pdf::loadView('pdf.recap', $dataForPdf);

        $filename = 'laporan-rekapitulasi-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }
}
