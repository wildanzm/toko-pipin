<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReturController extends Controller
{
    /**
     * Fungsi untuk memastikan string adalah UTF-8 yang valid untuk DomPDF.
     */
    private function cleanForPdf($string): string
    {
        if (is_null($string)) {
            return '';
        }
        return mb_convert_encoding((string)$string, 'HTML-ENTITIES', 'UTF-8');
    }

    /**
     * Membuat dan mengunduh laporan pengembalian (retur) dalam format PDF.
     */
    public function streamRetur(Request $request)
    {
        // Status yang menandakan pengembalian yang sudah selesai/dikonfirmasi
        $returnStatus = 'returned';

        $query = OrderItem::with(['order.user', 'product'])
            ->whereHas('order', function ($q_order) use ($returnStatus) {
                $q_order->where('status', $returnStatus);
            });

        // Anda bisa menambahkan filter tanggal dari request jika diperlukan di masa depan
        // ->when($request->input('month'), function ($q, $month) { ... });

        $returnedItems = $query->orderByDesc(
            Order::select('updated_at') // Urutkan berdasarkan kapan status diubah menjadi 'returned'
                ->whereColumn('id', 'order_items.order_id')
                ->latest('updated_at')
                ->limit(1)
        )->get();

        // Menyiapkan data untuk dikirim ke view PDF
        $dataForPdf = [
            'returnedItems' => $returnedItems,
            'reportTitle' => 'Laporan Retur Penjualan',
            'reportDate' => Carbon::now()->locale('id_ID.UTF-8')->translatedFormat('l, d F Y'),
            'totalReturnAmount' => $returnedItems->sum(fn($item) => $item->price * $item->quantity),
            'totalReturnQuantity' => $returnedItems->sum('quantity'),
        ];

        // Ganti 'pdf.return_report_template' dengan path view Anda
        $pdf = Pdf::loadView('pdf.retur', $dataForPdf)->setPaper('a4', 'landscape');

        $filename = 'laporan-retur-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }
}
