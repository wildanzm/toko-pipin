<?php

namespace App\Livewire\Components;

use App\Models\Cart;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CartCounter extends Component
{

    public int $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount(); // Muat jumlah awal saat komponen di-mount
    }

    /**
     * Metode ini akan dipanggil ketika event 'cartUpdated' diterima.
     * Event 'cartUpdated' harus di-dispatch dari komponen lain
     * setelah item ditambahkan atau dihapus dari keranjang.
     */
    #[On('cartUpdated')] // Listener untuk event global 'cartUpdated'
    public function updateCartCount()
    {
        if (Auth::check()) {
            // Menghitung total kuantitas semua item di keranjang pengguna
            $this->cartCount = Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            // Logika untuk keranjang tamu (guest) jika ada (misalnya, dari session)
            // Untuk saat ini, kita set 0 jika tidak login dan keranjang berbasis database
            $this->cartCount = 0;
            // Contoh jika menggunakan package session cart seperti darryldecode/cart:
            // $this->cartCount = \Cart::session(session()->getId())->getTotalQuantity();
        }
    }

    public function render()
    {
        return view('livewire.components.cart-counter');
    }
}
