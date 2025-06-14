<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB; // Pastikan DB Facade diimpor
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
#[Title('Penjualan')]
class Sale extends Component
{
    // ... (properti dan metode lain tetap sama) ...
    use WithPagination;

    public int $perPage = 10;

    public string $filterPeriod = 'monthly';
    public $filterMonth;
    public $filterYear;
    public $filterPaymentMethod = '';

    public int $totalProductsSold = 0;
    public float $totalSalesRevenue = 0;

    public function mount()
    {
        if ($this->filterPeriod === 'custom') {
            $this->filterMonth = Carbon::now()->month;
            $this->filterYear = Carbon::now()->year;
        }
        $this->calculateTotals();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'filterPeriod' && $this->filterPeriod !== 'custom') {
            $this->filterMonth = null;
            $this->filterYear = null;
        }
        $this->resetPage();
        $this->calculateTotals();
    }

    protected function getFilteredQuery()
    {
        $validSaleStatuses = ['completed', 'delivered', 'paid', 'shipped'];

        return OrderItem::with(['order.user', 'product'])
            ->whereHas('order', function ($q_order) use ($validSaleStatuses) {
                $q_order->whereIn('status', $validSaleStatuses)
                    ->when($this->filterPaymentMethod, function ($q_payment, $method) {
                        $q_payment->where('payment_method', $method);
                    })
                    ->when($this->filterPeriod, function ($q_period, $period) {
                        // Logika untuk periode cepat
                        match ($period) {
                            'today' => $q_period->whereDate('created_at', Carbon::today()),
                            'weekly' => $q_period->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                            'monthly' => $q_period->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month),
                            'last_month' => $q_period->whereYear('created_at', Carbon::now()->subMonth()->year)->whereMonth('created_at', Carbon::now()->subMonth()->month),
                            'annual' => $q_period->whereYear('created_at', Carbon::now()->year),
                            default => null, // Untuk 'custom' atau 'all', tidak ada filter tanggal di sini
                        };
                    })
                    // Logika untuk filter bulan & tahun spesifik (jika periode 'custom')
                    ->when($this->filterPeriod === 'custom' && $this->filterYear, function ($q_year, $year) {
                        $q_year->whereYear('created_at', $this->filterYear);
                    })
                    ->when($this->filterPeriod === 'custom' && $this->filterMonth, function ($q_month, $month) {
                        $q_month->whereMonth('created_at', $this->filterMonth);
                    });
            });
    }

    public function calculateTotals()
    {
        $allSalesForTotals = $this->getFilteredQuery()->get();
        $this->totalProductsSold = $allSalesForTotals->sum('quantity');
        $this->totalSalesRevenue = $allSalesForTotals->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Mengarahkan ke route ekspor PDF dengan membawa filter aktif.
     */
    public function exportPdf()
    {
        // Membuat query string dari filter yang aktif
        $queryParams = http_build_query([
            'period' => $this->filterPeriod,
            'month' => $this->filterMonth,
            'year' => $this->filterYear,
            'paymentMethod' => $this->filterPaymentMethod,
        ]);

        $url = route('admin.reports.sales.stream') . '?' . $queryParams;

        // Mengarahkan ke URL baru di tab baru
        return $this->redirect($url, navigate: false);
    }

    // Metode getReportTitle() dan getFilenameSuffix() tetap sama seperti sebelumnya
    public function getReportTitle(): string
    {
        $title = 'Laporan Penjualan ';

        if ($this->filterPaymentMethod) {
            $title .= 'Metode ' . ucfirst($this->filterPaymentMethod) . ' ';
        }

        if ($this->filterMonth && $this->filterYear) {
            $title .= 'Bulan ' . Carbon::create()->month($this->filterMonth)->translatedFormat('F') . ' ' . $this->filterYear;
        } elseif ($this->filterYear) {
            $title .= 'Tahun ' . $this->filterYear;
        } elseif ($this->filterMonth) {
            $title .= 'Setiap Bulan ' . Carbon::create()->month($this->filterMonth)->translatedFormat('F');
        } else {
            $title .= 'Keseluruhan';
        }

        return $title;
    }

    private function getFilenameSuffix(): string
    {
        $parts = [];
        if ($this->filterPaymentMethod) {
            $parts[] = $this->filterPaymentMethod;
        }
        if ($this->filterMonth) {
            $parts[] = 'bulan-' . str_pad($this->filterMonth, 2, '0', STR_PAD_LEFT);
        }
        if ($this->filterYear) {
            $parts[] = 'tahun-' . $this->filterYear;
        }

        if (empty($parts)) {
            return 'keseluruhan-' . now()->format('Ymd');
        }

        return implode('-', $parts);
    }

    public function render()
    {
        $paginatedSalesItems = $this->getFilteredQuery()
            ->select('order_items.*')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->orderBy('orders.created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.sale', [
            'salesItems' => $paginatedSalesItems,
        ]);
    }
}
