<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Untuk Str::ucfirst
use Carbon\Carbon; // Untuk logika batas waktu (opsional)

#[Layout('components.layouts.app')]
#[Title('Daftar Transaksi - Gadget Official')]
class Transaction extends Component
{
    use WithPagination;

    public int $perPage = 5;

    public function streamUserInvoice($orderId)
    {
        $orderExists = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->exists();
        if (!$orderExists) {
            session()->flash('error', 'Invoice tidak ditemukan atau Anda tidak berhak mengaksesnya.');
            return;
        }
        return redirect()->route('user.invoice.stream', ['orderId' => $orderId]);
    }

    public function confirmOrderReceived($orderId)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $orderId)->first();

        if ($order && (strtolower($order->status) === 'shipped' || strtolower($order->status) === 'delivered')) {
            $order->status = 'completed'; // Pesanan dianggap selesai oleh pengguna
            $order->save();
            session()->flash('message', 'Pesanan ' . $order->order_code . ' telah dikonfirmasi selesai.');
        } else {
            session()->flash('error', 'Status pesanan tidak dapat diubah atau pesanan tidak ditemukan.');
        }
    }

    /**
     * Pengguna mengajukan pengembalian barang.
     * Status diubah menjadi 'return_requested'.
     * Admin kemudian akan meninjau pengajuan ini.
     */
    public function requestReturn($orderId)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $orderId)->first();

        if (!$order) {
            session()->flash('error', 'Pesanan tidak ditemukan.');
            return;
        }

        // Status pesanan yang diizinkan untuk mengajukan pengembalian
        $allowedStatusesForReturn = ['delivered', 'shippedPtr', 'completed'];
        // Status yang menandakan proses pengembalian sudah berjalan atau selesai
        $returnInProgressStatuses = ['return_requested', 'awaiting_return', 'returned', 'return_rejected'];

        if (in_array(strtolower($order->status), $returnInProgressStatuses)) {
            session()->flash('info', 'Pengajuan pengembalian untuk pesanan ' . $order->order_code . ' sudah pernah diajukan atau sedang dalam proses.');
            return;
        }

        if (in_array(strtolower($order->status), $allowedStatusesForReturn)) {
            // Opsional: Tambahkan logika batas waktu pengajuan pengembalian
            // Misalnya, 7 hari setelah status 'delivered'
            // if (strtolower($order->status) === 'delivered' && $order->updated_at->lt(Carbon::now()->subDays(7))) {
            //     session()->flash('error', 'Batas waktu 7 hari untuk pengajuan pengembalian telah terlewati.');
            //     return;
            // }

            $order->status = 'return_requested'; // Status baru: "Pengajuan Pengembalian"
            $order->save();

            // TODO: Implementasikan pengiriman notifikasi ke admin (misalnya melalui Event & Listener atau Email)
            // event(new \App\Events\ReturnRequested($order)); // Contoh event

            session()->flash('message', 'Pengajuan pengembalian untuk pesanan ' . $order->order_code . ' telah berhasil dikirim. Mohon tunggu konfirmasi dari admin.');
        } else {
            session()->flash('error', 'Pesanan dengan status "' . Str::title($order->status) . '" tidak dapat diajukan untuk pengembalian.');
        }
    }

    /**
     * Placeholder untuk mencetak resi pengembalian.
     * Tombol ini akan muncul di view jika status pesanan adalah 'awaiting_return'
     * (setelah admin menyetujui pengajuan dan mengubah statusnya).
     */
    public function printReturnLabel($orderId)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $orderId)->firstOrFail();

        if (strtolower($order->status) !== 'awaiting_return') {
            session()->flash('error', 'Resi pengembalian belum tersedia atau pengajuan belum disetujui admin.');
            return;
        }
        // Logika untuk menampilkan atau men-generate PDF resi pengembalian yang disediakan/dibuat oleh Admin
        session()->flash('info', 'Fitur cetak resi pengembalian untuk ' . $order->order_code . ' akan menampilkan resi dari Admin.');
        // Contoh: return redirect()->route('user.return.label.pdf', $order->id);
    }

    /**
     * Placeholder untuk melihat laporan/detail pengembalian setelah proses selesai.
     */
    public function viewReturnReport($orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        session()->flash('info', 'Fitur laporan pengembalian untuk ' . $order->order_code . ' sedang dikembangkan.');
        // Redirect ke halaman detail status pengembalian atau tampilkan modal
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.transaction', [ // Pastikan nama view Anda: resources/views/livewire/transaction.blade.php
            'orders' => $orders,
        ]);
    }
}
