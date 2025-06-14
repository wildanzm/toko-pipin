<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')] // Sesuaikan dengan layout utama Anda
#[Title('Daftar Tagihan Kredit')]
class Installment extends Component
{
    use WithPagination;

    public int $perPage = 5; // Jumlah pesanan per halaman

    public function render()
    {
        // Kueri untuk mengambil data pesanan cicilan milik pengguna
        $ordersQuery = Order::with(['items.product', 'installments'])
            ->where('user_id', Auth::id())
            ->where('payment_method', 'installment')
            ->whereHas('installments')
            ->orderBy('created_at', 'desc');

        $orders = $ordersQuery->paginate($this->perPage);

        return view('livewire.installment', [ // Pastikan nama view ini benar
            'orders' => $orders,
        ]);
    }
}
