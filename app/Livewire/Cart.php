<?php

namespace App\Livewire;

use App\Models\Cart as CartModel; // Menggunakan alias jika ada konflik
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Installment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Layout; // Untuk Livewire 3
use Livewire\Attributes\Title;  // Untuk Livewire 3

#[Layout('components.layouts.app')] // Sesuaikan dengan layout utama Anda
#[Title('Keranjang')]

class Cart extends Component
{
    public $cartItems = [];
    public $shippingName;
    public $shippingAddress;

    public $paymentMethod = 'cash';
    public $installmentPlan = ''; // 3, 6, 12 (bulan)

    public $subTotal = 0;
    public $interestAmount = 0;
    public $grandTotal = 0;

    public $installmentDetails = [];

    protected $rules = [
        'shippingName' => 'required|string|max:255',
        'shippingAddress' => 'required|string|max:1000',
        'paymentMethod' => 'required|in:cash,installment',
        'installmentPlan' => 'required_if:paymentMethod,installment|in:3,6,12,""', // "" untuk '-- Pilih Tenor --'
    ];

    protected $messages = [
        'shippingName.required' => 'Nama penerima wajib diisi.',
        'shippingAddress.required' => 'Alamat pengiriman wajib diisi.',
        'paymentMethod.required' => 'Metode pembayaran wajib dipilih.',
        'installmentPlan.required_if' => 'Pilih tenor cicilan jika metode pembayaran adalah cicilan.',
        'installmentPlan.in' => 'Tenor cicilan tidak valid.',
    ];

    public function mount()
    {
        $this->loadCartItems();
        if (Auth::check()) {
            $this->shippingName = Auth::user()->name;
            $this->shippingAddress = Auth::user()->address;
        }
    }

    public function loadCartItems()
    {
        if (Auth::check()) {
            $this->cartItems = CartModel::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $this->cartItems = collect();
        }
        $this->calculateTotals();
    }

    public function incrementQuantity($cartId)
    {
        $item = CartModel::find($cartId);
        if ($item && $item->user_id == Auth::id()) {
            if ($item->product->stock > $item->quantity) {
                $item->quantity++;
                $item->save();
                $this->loadCartItems();
            } else {
                session()->flash('cartError', 'Stok produk ' . $item->product->name . ' tidak mencukupi.');
            }
        }
    }

    public function decrementQuantity($cartId)
    {
        $item = CartModel::find($cartId);
        if ($item && $item->user_id == Auth::id() && $item->quantity > 1) {
            $item->quantity--;
            $item->save();
            $this->loadCartItems();
        } elseif ($item && $item->user_id == Auth::id() && $item->quantity <= 1) {
            $this->removeItem($cartId);
        }
    }

    public function updateQuantity($cartId, $quantity)
    {
        $item = CartModel::find($cartId);
        $newQuantity = filter_var($quantity, FILTER_VALIDATE_INT);

        if ($item && $item->user_id == Auth::id() && $newQuantity !== false && $newQuantity >= 1) {
            if ($item->product->stock >= $newQuantity) {
                $item->quantity = $newQuantity;
                $item->save();
            } else {
                $item->quantity = $item->product->stock > 0 ? $item->product->stock : 1; // Set ke max stock atau 1
                $item->save();
                session()->flash('cartError', 'Kuantitas ' . $item->product->name . ' disesuaikan dengan stok (' . $item->product->stock . ').');
            }
            $this->loadCartItems();
        } else if ($item && $item->user_id == Auth::id() && ($newQuantity === false || $newQuantity < 1)) {
            // Jika input tidak valid atau kurang dari 1, set ke 1 atau hapus
            $item->quantity = 1;
            $item->save();
            $this->loadCartItems();
            session()->flash('cartError', 'Kuantitas minimal adalah 1.');
        }
    }

    public function removeItem($cartId)
    {
        $item = CartModel::find($cartId);
        if ($item && $item->user_id == Auth::id()) {
            $item->delete();
            $this->loadCartItems();
            session()->flash('cartMessage', 'Produk berhasil dihapus dari keranjang.');
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['paymentMethod', 'installmentPlan'])) {
            $this->validateOnly($propertyName);
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->subTotal = $this->cartItems->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });

        // Hapus Asuransi, Biaya Pengiriman Tetap, PPN dari properti dan perhitungan
        // $this->ppnAmount = 0; // Dihapus
        // $this->insuranceFee = 0; // Dihapus
        // public $shippingFee = 0; // Anda bisa set default 0 atau hapus jika tidak ada biaya pengiriman tetap

        $this->interestAmount = 0;
        // Total sebelum grand total sekarang hanya subTotal (plus biaya lain jika ada di masa depan)
        $totalBeforeGrandTotal = $this->subTotal; // + $this->shippingFee (jika masih ada)

        if ($this->paymentMethod === 'installment' && !empty($this->installmentPlan) && $this->installmentPlan > 0) {
            $this->interestAmount = $this->subTotal * 0.05; // Bunga 5% dari subtotal
            $totalWithInterest = $totalBeforeGrandTotal + $this->interestAmount;
            $this->grandTotal = $totalWithInterest;

            $this->installmentDetails = [];
            if ((int)$this->installmentPlan > 0) {
                $monthlyPayment = $totalWithInterest / (int)$this->installmentPlan;
                for ($i = 1; $i <= (int)$this->installmentPlan; $i++) {
                    $this->installmentDetails[] = [
                        'due_date' => Carbon::now()->addMonthsNoOverflow($i),
                        'amount' => $monthlyPayment,
                        'month_text' => $i . ' dari ' . $this->installmentPlan . ' bulan', // Tambahan info bulan
                    ];
                }
            }
        } else {
            $this->grandTotal = $totalBeforeGrandTotal;
            $this->installmentDetails = [];
        }
    }

    public function placeOrder()
    {
        $validatedData = $this->validate();
        $this->calculateTotals();

        if ($this->cartItems->isEmpty()) {
            session()->flash('cartError', 'Keranjang Anda kosong. Tidak dapat membuat pesanan.');
            return;
        }

        // Variabel $orderCode akan didefinisikan di dalam transaksi untuk akurasi nomor urut
        $finalOrderCode = '';

        DB::transaction(function () use ($validatedData, &$finalOrderCode) { // Perhatikan penggunaan & untuk $finalOrderCode
            // --- AWAL BAGIAN PEMBUATAN KODE PESANAN BARU ---
            $datePart = Carbon::now()->format('Ymd');

            // Menghitung jumlah pesanan yang sudah ada untuk hari ini untuk menentukan nomor urut berikutnya
            // Kueri ini berada di dalam transaksi untuk konsistensi yang lebih baik
            $todaysOrderCount = Order::whereDate('created_at', Carbon::today())->count();
            $sequenceNumber = $todaysOrderCount + 1;

            // Format nomor urut menjadi 3 digit dengan padding nol (misalnya, 001, 012, 123)
            // Anda bisa mengubah '3' menjadi angka lain jika mengharapkan lebih dari 999 pesanan per hari
            $paddedSequence = str_pad($sequenceNumber, 3, '0', STR_PAD_LEFT);

            $orderCode = 'GO-' . $datePart . $paddedSequence;
            $finalOrderCode = $orderCode; // Simpan ke variabel luar transaksi
            // --- AKHIR BAGIAN PEMBUATAN KODE PESANAN BARU ---

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_code' => $orderCode, // Menggunakan kode pesanan yang baru
                'total_amount' => $this->grandTotal,
                'status' => 'pending',
                'shipping_address' => $validatedData['shippingAddress'],
                'name_receiver' => $validatedData['shippingName'],
                'payment_method' => $validatedData['paymentMethod'],
                'installment_plan' => ($validatedData['paymentMethod'] === 'installment' && !empty($validatedData['installmentPlan'])) ? $validatedData['installmentPlan'] . ' Bulan' : null,
                'sub_total' => $this->subTotal,
                'interest_amount' => $this->interestAmount,
                // Kolom lain yang relevan seperti shipping_fee, insurance_fee, ppn_amount bisa ditambahkan jika masih digunakan
            ]);

            foreach ($this->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            if ($validatedData['paymentMethod'] === 'installment' && !empty($this->installmentDetails)) {
                foreach ($this->installmentDetails as $detail) {
                    Installment::create([
                        'order_id' => $order->id,
                        'amount' => $detail['amount'],
                        'due_date' => $detail['due_date']->toDateString(),
                        'is_paid' => false,
                    ]);
                }
            }

            CartModel::where('user_id', Auth::id())->delete();
            $this->loadCartItems();
        });


        // Pastikan route 'order.success' sudah ada dan menerima parameter
        session()->flash('orderSuccess', 'Pesanan Anda berhasil dibuat! Kode Pesanan: ' . $finalOrderCode);
        return redirect()->route('transaction');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
