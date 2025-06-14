<?php

namespace App\Livewire\Admin;

use App\Models\Order as OrderModel;
use Livewire\Component;
use App\Models\OrderItem;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;


#[Layout('components.layouts.admin')]
#[Title('Daftar Pesanan')]
class Order extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';
    public string $filterStatus = '';

    // ... (metode markAsPaid, markAsShipped, markAsDelivered, confirmOrderCompleted tetap sama) ...
    public function markAsPaid(int $orderId)
    {
        $order = OrderModel::find($orderId);
        if ($order && strtolower($order->status) === 'pending') {
            $order->status = 'paid';
            $order->save();
            session()->flash('message', 'Status pesanan ' . $order->order_code . ' telah diubah menjadi "Sudah Dibayar".');
        } else {
            session()->flash('error', 'Pesanan tidak ditemukan atau status tidak bisa diubah.');
        }
    }

    public function markAsShipped(int $orderId)
    {
        $order = OrderModel::find($orderId);
        if ($order && (strtolower($order->status) === 'paid' || strtolower($order->status) === 'processing')) {
            $order->status = 'shipped';
            $order->save();
            session()->flash('message', 'Status pesanan ' . $order->order_code . ' telah diubah menjadi "Dalam Pengiriman".');
        } else {
            session()->flash('error', 'Pesanan tidak ditemukan atau status tidak memungkinkan untuk dikirim.');
        }
    }

    public function markAsDelivered(int $orderId)
    {
        $order = OrderModel::find($orderId);
        if ($order && strtolower($order->status) === 'shipped') {
            $order->status = 'delivered';
            $order->save();
            session()->flash('message', 'Status pesanan ' . $order->order_code . ' telah diubah menjadi "Sudah Diterima".');
        } else {
            session()->flash('error', 'Pesanan tidak ditemukan atau status tidak memungkinkan.');
        }
    }

    public function confirmOrderCompleted(int $orderId)
    {
        $order = OrderModel::find($orderId);
        if ($order && (strtolower($order->status) === 'delivered' || strtolower($order->status) === 'shipped')) {
            $order->status = 'completed';
            $order->save();
            session()->flash('message', 'Pesanan ' . $order->order_code . ' telah ditandai "Selesai".');
        } else {
            session()->flash('error', 'Pesanan tidak ditemukan atau status tidak memungkinkan untuk diselesaikan.');
        }
    }

    /**
     * Admin menyetujui permintaan pengembalian dari pengguna.
     * Mengubah status dari 'return_requested' menjadi 'awaiting_return'.
     */
    public function approveReturnRequest(int $orderId)
    {
        $order = OrderModel::find($orderId);
        if ($order && strtolower($order->status) === 'return_requested') {
            $order->status = 'awaiting_return'; // Menunggu barang fisik dikembalikan oleh pelanggan
            $order->save();
            // TODO: Kirim notifikasi ke pengguna bahwa pengajuan disetujui dan instruksi pengembalian
            session()->flash('message', 'Pengajuan pengembalian untuk pesanan ' . $order->order_code . ' telah disetujui. Menunggu barang diterima.');
        } else {
            session()->flash('error', 'Tidak dapat menyetujui pengembalian untuk status pesanan saat ini.');
        }
    }

    /**
     * Admin mengkonfirmasi penerimaan barang yang dikembalikan atau menolak pengajuan.
     * Untuk konfirmasi penerimaan: action = 'mark_returned'
     * Untuk menolak pengajuan awal: action = 'reject_return_request'
     */
    public function processReturnRequest(int $orderId, string $action)
    {
        $order = OrderModel::find($orderId);
        if (!$order) {
            session()->flash('error', 'Pesanan tidak ditemukan.');
            return;
        }

        if ($action === 'mark_returned' && strtolower($order->status) === 'awaiting_return') {
            $order->status = 'returned'; // Barang Sudah Diterima Admin dan Diproses
            $order->save();
            // TODO: Logika refund atau penggantian barang
            session()->flash('message', 'Barang untuk pesanan ' . $order->order_code . ' telah dikonfirmasi diterima dan status diubah menjadi "Dikembalikan".');
        } elseif ($action === 'reject_return_request' && strtolower($order->status) === 'return_requested') {
            // Admin menolak pengajuan pengembalian awal dari user
            $order->status = $order->getOriginal('status_before_return_request') ?? 'completed'; // Kembalikan ke status sebelum atau 'completed'
            // Anda mungkin perlu menyimpan status sebelumnya jika ingin kembali ke sana.
            // Atau cukup beri catatan/alasan penolakan.
            $order->save();
            // TODO: Kirim notifikasi ke pengguna bahwa pengajuan ditolak
            session()->flash('message', 'Pengajuan pengembalian untuk pesanan ' . $order->order_code . ' telah ditolak.');
        } else {
            session()->flash('error', 'Aksi pengembalian tidak valid untuk status pesanan saat ini.');
        }
    }

    public function render()
    {
        $orderItemsQuery = OrderItem::with(['order.user', 'product'])
            ->select('order_items.*')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($this->search, function ($query) {
                $query->whereHas('order', function ($q_order) {
                    $q_order->where('order_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q_user) {
                            $q_user->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                    ->orWhereHas('product', function ($q_product) {
                        $q_product->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus, function ($query) {
                if ($this->filterStatus !== '') {
                    $query->whereHas('order', function ($q_order) {
                        $q_order->where('status', $this->filterStatus);
                    });
                }
            })
            ->orderBy('orders.created_at', 'desc');

        $orderItems = $orderItemsQuery->paginate($this->perPage);

        return view('livewire.admin.order', [
            'orderItems' => $orderItems,
        ]);
    }
}
