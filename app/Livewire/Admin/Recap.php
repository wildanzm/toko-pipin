<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use App\Models\Installment;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;


#[Layout('components.layouts.admin')]
#[Title('Rekapitulasi')]
class Recap extends Component
{
    // Properti untuk menyimpan hasil perhitungan
    public int $cashSalesCount = 0;
    public float $cashSalesTotal = 0;
    public int $creditSalesCount = 0;
    public float $creditSalesTotal = 0;
    public int $returnCount = 0;
    public float $returnTotal = 0;
    public int $grossSalesCount = 0;
    public float $grossSalesTotal = 0;
    public float $netSalesTotal = 0;
    public int $totalReceivablesCount = 0;
    public float $totalReceivablesAmount = 0;
    public float $totalPaidReceivables = 0;
    public float $remainingReceivables = 0;

    public function mount()
    {
        $this->calculateRecap();
    }

    public function calculateRecap()
    {
        $validSaleStatuses = ['paid', 'processing', 'shipped', 'delivered', 'completed'];

        // 1. Penjualan Tunai
        $cashSales = Order::where('payment_method', 'cash')->whereIn('status', $validSaleStatuses)->get();
        $this->cashSalesCount = $cashSales->count();
        // Untuk tunai, total_amount sama dengan sub_total (harga produk)
        $this->cashSalesTotal = $cashSales->sum('total_amount');

        // 2. Penjualan Kredit (Cicilan)
        $creditSales = Order::where('payment_method', 'installment')->whereIn('status', $validSaleStatuses)->get();
        $this->creditSalesCount = $creditSales->count();
        // Untuk kredit, total_amount sudah termasuk bunga
        $this->creditSalesTotal = $creditSales->sum('total_amount');

        // --- PERBAIKAN LOGIKA PERHITUNGAN ---

        // 3. Retur Penjualan (Nilai retur berdasarkan harga produk saja)
        $returnedOrders = Order::whereIn('status', ['awaiting_return', 'returned'])->get();
        $this->returnCount = $returnedOrders->count();
        // Asumsi `sub_total` ada di tabel `orders`. Jika tidak, kita harus join dan sum dari order_items.
        // Berdasarkan error sebelumnya, kita asumsikan tidak ada kolom 'sub_total'.
        // Maka kita hitung manual:
        $this->returnTotal = $returnedOrders->reduce(function ($carry, $order) {
            return $carry + $order->items->sum(fn($item) => $item->price * $item->quantity);
        }, 0);

        // 4. Total Penjualan Kotor (Total pendapatan termasuk bunga)
        $this->grossSalesCount = $this->cashSalesCount + $this->creditSalesCount;
        $this->grossSalesTotal = $this->cashSalesTotal + $this->creditSalesTotal;

        // 5. Total Penjualan Bersih (Total harga produk saja, dikurangi retur)
        // Hitung total subtotal dari semua penjualan valid
        $totalSubTotal = $cashSales->sum('total_amount') + $creditSales->sum(function ($order) {
            // Kita ambil subtotal dari grand total dikurangi bunga
            $interest = $order->total_amount - ($order->total_amount / 1.05); // Menghitung bunga dari grand total
            return $order->total_amount - $interest;
            // Atau jika Anda sudah menambahkan kolom `sub_total` dan `interest_amount`:
            // return $order->sub_total;
        });
        $this->netSalesTotal = $totalSubTotal - $this->returnTotal;

        // 6. Total Piutang Kredit (Total nilai transaksi kredit)
        $this->totalReceivablesCount = $this->creditSalesCount;
        $this->totalReceivablesAmount = $this->creditSalesTotal;

        // 7. Sudah Dibayar (dari Piutang Kredit)
        $this->totalPaidReceivables = Installment::where('is_paid', true)
            ->whereHas('order', function ($query) use ($validSaleStatuses) {
                $query->whereIn('status', array_merge($validSaleStatuses, ['awaiting_return', 'returned']));
            })
            ->sum('amount');

        // 8. Sisa Piutang
        $this->remainingReceivables = $this->totalReceivablesAmount - $this->totalPaidReceivables;
    }

    public function exportPdf()
    {
        $url = route('admin.recap.stream');

        // Menggunakan event JavaScript untuk membuka di tab baru
        return $this->redirect($url, navigate: false);
    }

    public function render()
    {
        return view('livewire.admin.recap');
    }
}
