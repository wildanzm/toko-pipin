<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Installment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.admin')]
#[Title('Daftar Tagihan Kredit - Gadget Official')]
class Credit extends Component
{
    use WithPagination;

    public int $perPage = 10;
    public string $search = '';
    public string $filterStatus = '';

    public function mount()
    {
        // Inisialisasi awal jika diperlukan
    }

    public function exportPdf()
    {
        $queryParams = http_build_query([
            'search' => $this->search,
            'status' => $this->filterStatus,
        ]);

        $url = route('admin.credit.stream') . '?' . $queryParams;

        return $this->redirect($url, navigate: false);
    }

    public function markInstallmentAsPaid(int $installmentId)
    {
        DB::transaction(function () use ($installmentId) {
            $installment = Installment::with('order')->lockForUpdate()->find($installmentId);

            if ($installment && !$installment->is_paid) {
                $lateFee = 0;
                $lateDays = 0;
                $dueDate = Carbon::parse($installment->due_date)->startOfDay();
                $today = Carbon::now(config('app.timezone', 'UTC'))->startOfDay();

                // Hitung denda jika terlambat
                if ($today->gt($dueDate)) {
                    $lateDays = $today->diffInDays($dueDate);
                    if ($lateDays > 0) {
                        $lateFee = ($installment->amount * 0.01) * $lateDays; // Denda 1% per hari
                    }
                }

                // Simpan semua data pembayaran dan denda ke database
                $installment->is_paid = true;
                $installment->paid_at = Carbon::now();
                $installment->late_days = $lateDays; // Simpan jumlah hari terlambat
                $installment->late_fee = $lateFee;   // Simpan jumlah denda
                $installment->save();

                // Cek apakah semua cicilan sudah lunas untuk update status order utama
                $order = $installment->order;
                if ($order->installments()->where('is_paid', false)->doesntExist()) {
                    $order->status = 'completed';
                    $order->save();
                    session()->flash('message', 'Semua cicilan untuk pesanan ' . $order->order_code . ' telah lunas. Status pesanan diperbarui menjadi Selesai.');
                } else {
                    session()->flash('message', 'Cicilan untuk pesanan ' . $order->order_code . ' telah ditandai lunas.');
                }
            } else {
                session()->flash('error', 'Tagihan tidak ditemukan atau sudah lunas.');
            }
        });
    }

    public function render()
    {
        // Logika kueri tidak perlu lagi menghitung denda secara dinamis
        $ordersQuery = Order::with(['user', 'items.product', 'installments'])
            ->where('payment_method', 'installment')
            ->when($this->search, function ($query) {
                $query->where('order_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q_user) {
                        $q_user->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus, function ($query) {
                if ($this->filterStatus === 'pending_installments') {
                    $query->whereHas('installments', fn($q) => $q->where('is_paid', false));
                } elseif ($this->filterStatus === 'fully_paid') {
                    $query->whereDoesntHave('installments', fn($q) => $q->where('is_paid', false))->whereHas('installments');
                }
            })
            ->orderBy('created_at', 'desc');

        $orders = $ordersQuery->paginate($this->perPage);

        return view('livewire.admin.credit', [
            'orders' => $orders,
        ]);
    }
}
