<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Home')]

class LandingPage extends Component
{
    use WithPagination;

    public int $perPage = 10; // Jumlah produk per halaman (jika menggunakan paginasi)
    // Anda bisa menambahkan properti lain seperti filter, sorting, dll.

    /**
     * Metode untuk menambahkan produk ke keranjang.
     * Implementasi sebenarnya akan bergantung pada sistem keranjang Anda.
     */
    public function addToCart($productId)
    {
        if (!Auth::check()) {
            session()->flash('cart_error', 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.');
            return;
        }

        $product = Product::find($productId);

        if (!$product) {
            session()->flash('cart_error', 'Produk tidak ditemukan.');
            return;
        }

        if ($product->stock <= 0) {
            session()->flash('cart_error', 'Maaf, stok produk ' . $product->name . ' saat ini habis.');
            return;
        }

        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($product->stock > $cartItem->quantity) {
                $cartItem->increment('quantity');
                // Pesan sukses ketika kuantitas ditambah
                session()->flash('cart_message', $product->name . ' berhasil ditambahkan lagi ke keranjang!');
            } else {
                session()->flash('cart_error', 'Tidak dapat menambah jumlah ' . $product->name . ', stok tidak mencukupi.');
                return;
            }
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
            // Pesan sukses ketika produk baru ditambahkan
            session()->flash('cart_message', $product->name . ' berhasil ditambahkan ke keranjang!');
        }

        // Kirim event untuk update jumlah item di keranjang (di header, dll.)
        $this->dispatch('cartUpdated');
    }
    
    public function render()
    {
        // Ambil produk dari database. Contoh: 10 produk terbaru dengan paginasi.
        // Sesuaikan kueri ini dengan kebutuhan Anda (misalnya, produk unggulan, produk diskon, dll.)
        $products = Product::latest() // Urutkan berdasarkan terbaru
            // ->where('is_featured', true) // Contoh filter
            ->paginate($this->perPage);

        return view('livewire.landing-page', [ // Pastikan nama view ini sesuai
            'products' => $products,
        ]);
    }
}
