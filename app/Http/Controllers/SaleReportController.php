<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Order; // Pastikan model Order diimpor jika belum ada

class SaleReportController extends Controller
{
    private function cleanStringForPdf($string): string
    {
        if (is_null($string)) {
            return '';
        }
        return mb_convert_encoding((string)$string, 'HTML-ENTITIES', 'UTF-8');
    }

    public function streamSalesReport(Request $request)
    {
        // Ambil SEMUA parameter filter dari request
        $filterPeriod = $request->input('period', 'all'); // 'today', 'weekly', 'monthly', 'custom', 'all', dll.
        $filterMonth = $request->input('month');
        $filterYear = $request->input('year');
        $filterPaymentMethod = $request->input('paymentMethod');

        $validSaleStatuses = ['completed', 'delivered', 'paid', 'shipped'];

        $query = OrderItem::with(['order.user', 'product'])
            ->whereHas('order', function ($q_order) use ($validSaleStatuses, $filterPaymentMethod, $filterYear, $filterMonth, $filterPeriod) {
                $q_order->whereIn('status', $validSaleStatuses)
                    ->when($filterPaymentMethod, function ($q_payment, $method) {
                        $q_payment->where('payment_method', $method);
                    })
                    // Logika filter tanggal yang diperbarui
                    ->when($filterPeriod, function ($q_date, $period) use ($filterMonth, $filterYear) {
                        switch ($period) {
                            case 'today':
                                $q_date->whereDate('created_at', Carbon::today());
                                break;
                            case 'weekly':
                                $q_date->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                                break;
                            case 'monthly':
                                $q_date->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month);
                                break;
                            case 'last_month':
                                $q_date->whereYear('created_at', Carbon::now()->subMonth()->year)
                                    ->whereMonth('created_at', Carbon::now()->subMonth()->month);
                                break;
                            case 'annual':
                                $q_date->whereYear('created_at', Carbon::now()->year);
                                break;
                            case 'custom':
                                // Jika modenya custom, gunakan filter bulan dan tahun spesifik
                                $q_date->when($filterYear, function ($q, $year) {
                                    $q->whereYear('created_at', $year);
                                })
                                    ->when($filterMonth, function ($q, $month) {
                                        $q->whereMonth('created_at', $month);
                                    });
                                break;
                                // Untuk 'all' atau default, tidak ada filter tanggal yang diterapkan
                        }
                    });
            });

        $salesItems = $query->get();

        // Membersihkan data string (logika ini tetap sama)
        $cleanedSalesItems = $salesItems->map(function ($item) { /* ... (kode pembersihan Anda) ... */
        });

        $dataForPdf = [
            'salesItems' => $salesItems, // Kirim data asli, pembersihan dilakukan di view
            'totalSalesRevenue' => $salesItems->sum(fn($i) => $i->price * $i->quantity),
            'totalProductsSold' => $salesItems->sum('quantity'),
            'reportTitle' => $this->getReportTitle($filterPeriod, $filterMonth, $filterYear, $filterPaymentMethod),
            'reportDate' => Carbon::now()->locale('id_ID.UTF-8')->translatedFormat('l, d F Y'),
            'reportPeriodInfo' => $this->getReportPeriodInfo($filterPeriod, $filterMonth, $filterYear, $filterPaymentMethod)
        ];

        $pdf = Pdf::loadView('pdf.sales-report', $dataForPdf)->setPaper('a4', 'landscape');
        $filename = 'laporan-penjualan-' . $this->getFilenameSuffix($filterPeriod, $filterMonth, $filterYear, $filterPaymentMethod) . '.pdf';

        return $pdf->stream($filename);
    }

    private function getReportPeriodInfo($period, $month, $year, $paymentMethod): string
    {
        $text = 'Periode: ';

        if ($period === 'custom') {
            if ($month && $year) {
                $text .= 'Bulan ' . Carbon::create()->month((int)$month)->locale('id')->translatedFormat('F') . ' ' . $year;
            } elseif ($year) {
                $text .= 'Tahun ' . $year;
            } elseif ($month) {
                $text .= 'Setiap Bulan ' . Carbon::create()->month((int)$month)->locale('id')->translatedFormat('F');
            } else {
                $text .= 'Semua Penjualan';
            }
        } else {
            $text .= match ($period) {
                'today' => 'Harian (' . Carbon::now()->locale('id')->translatedFormat('d F Y') . ')',
                'weekly' => 'Mingguan',
                'monthly' => 'Bulanan (' . Carbon::now()->locale('id')->translatedFormat('F Y') . ')',
                'last_month' => 'Bulan Lalu (' . Carbon::now()->subMonth()->locale('id')->translatedFormat('F Y') . ')',
                'annual' => 'Tahun Ini (' . Carbon::now()->year . ')',
            };
        }

        if ($paymentMethod) {
            $paymentMethodText = match (strtolower($paymentMethod)) {
                'cash' => 'Tunai',
                'installment' => 'Cicilan',
                default => ucfirst($paymentMethod),
            };
            $text .= ' | Metode Pembayaran: ' . $paymentMethodText;
        }

        return $text;
    }

    // Sesuaikan metode helper untuk menerima $filterPeriod
    private function getReportTitle($period, $month, $year, $paymentMethod): string
    {
        $title = 'Laporan Penjualan ';
        if ($paymentMethod) {
            $paymentMethodTitle = match (strtolower($paymentMethod)) {
                'cash' => 'Tunai',
                'installment' => 'Cicilan',
                default => ucfirst($paymentMethod),
            };
            $title .= 'Metode ' . ucfirst($paymentMethodTitle) . ' ';
        }

        $locale = 'id';

        if ($period === 'custom') {
            if ($month && $year) {
                $title .= 'Bulan ' . Carbon::create()->month((int)$month)->locale($locale)->translatedFormat('F') . ' ' . $year;
            } elseif ($year) {
                $title .= 'Tahun ' . $year;
            } elseif ($month) {
                $title .= 'Setiap Bulan ' . Carbon::create()->month((int)$month)->locale($locale)->translatedFormat('F');
            } else {
                $title .= 'Semua Penjualan';
            }
        } else {
            $title .= match ($period) {
                'today' => 'Harian',
                'weekly' => 'Mingguan',
                'monthly' => 'Bulanan',
                'last_month' => 'Bulan Lalu',
                'annual' => 'Tahun Ini',
            };
        }

        return $this->cleanStringForPdf($title);
    }

    private function getFilenameSuffix($period, $month, $year, $paymentMethod): string
    {
        $parts = [];
        if ($paymentMethod) {
            $parts[] = $paymentMethod;
        }

        if ($period === 'custom') {
            if ($month) {
                $parts[] = 'bulan-' . str_pad((int)$month, 2, '0', STR_PAD_LEFT);
            }
            if ($year) {
                $parts[] = 'tahun-' . $year;
            }
        } else {
            $parts[] = $period;
        }

        if (empty($parts)) {
            return 'semua-' . now()->format('Ymd');
        }
        return implode('-', $parts);
    }
}
