<div>
    {{-- Tautan ke halaman keranjang atau login --}}
    <a href="{{ Auth::check() ? route('cart') : route('register') }}" {{-- Pastikan nama route sudah benar --}}
        class="relative p-2 text-white hover:text-[#FFC736] transition-colors group" {{-- Ditambahkan 'group' untuk hover effect pada badge --}}
        aria-label="Keranjang Belanja">

        {{-- Div pembungkus ikon, dijadikan relative untuk positioning badge --}}
        <div class="relative w-7 h-7 sm:w-8 sm:h-8 flex shrink-0">
            <img src="{{ asset('assets/images/icons/cart.svg') }}" alt="Keranjang Belanja" class="w-full h-full">

            {{-- Badge Counter --}}
            @if ($cartCount > 0)
                <span
                    class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center 
                             rounded-full bg-red-600 text-xs font-bold text-white 
                             transform -translate-y-1/2 translate-x-1/2 {{-- Menggeser badge agar tengahnya di sudut kanan atas ikon --}}
                             sm:-translate-y-1/3 sm:translate-x-1/3 {{-- Penyesuaian halus untuk layar sm --}}
                             group-hover:scale-110 transition-transform duration-200 ease-out"
                    x-data="{ count: {{ $cartCount }} }" x-show="count > 0" x-text="count > 9 ? '9+' : count">
                    {{-- Angka diisi oleh x-text atau langsung dari Livewire --}}
                </span>
            @endif
        </div>
    </a>
</div>
