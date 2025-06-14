<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderItem;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;


#[Layout('components.layouts.admin')]
#[Title('Retur')]
class Retur extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public function exportPdf()
    {
        $url = route('admin.retur.stream');

        return $this->redirect($url, navigate: false);
    }

    public function render()
    {
        // Status yang menandakan pengembalian
        $returnStatuses = ['awaiting_return', 'returned'];

        $returnedItems = OrderItem::with(['order.user', 'product'])
            ->whereHas('order', function ($q_order) use ($returnStatuses) {
                $q_order->whereIn('status', $returnStatuses);
            })
            ->select('order_items.*')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->orderBy('orders.updated_at', 'desc') // Urutkan berdasarkan update terakhir (kapan status berubah)
            ->paginate($this->perPage);

        return view('livewire.admin.retur', [
            'returnedItems' => $returnedItems,
        ]);
    }
}
