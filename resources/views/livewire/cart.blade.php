<div>
    {{-- Judul Halaman --}}
    <div id="title" class="container max-w-[1130px] mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <div class="flex flex-col gap-5">
            <h1 class="font-bold text-3xl sm:text-4xl leading-9 text-black">Keranjang Belanja</h1>
        </div>
    </div>

    {{-- Konten Utama (Keranjang & Form Checkout) --}}
    <div id="cart-and-checkout"
        class="container max-w-[1130px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col mt-8 sm:mt-[50px] pb-16 sm:pb-[100px]">

        {{-- Notifikasi Flash --}}
        @if (session()->has('cartMessage'))
            <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('cartMessage') }}
            </div>
        @endif
        @if (session()->has('cartError'))
            <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('cartError') }}
            </div>
        @endif
        @if (session()->has('orderSuccess'))
            <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('orderSuccess') }}
            </div>
        @endif

        {{-- Cek jika keranjang kosong --}}
        @if ($cartItems->isEmpty())
            {{-- Tampilan Keranjang Kosong - Ditempatkan di tengah container utama --}}
            <div class="w-full flex justify-center items-center py-16"> {{-- Wrapper untuk centering global --}}
                <div class="text-center py-10 bg-white rounded-xl shadow border border-[#E5E5E5] max-w-md w-full">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Keranjang Anda Kosong</h3>
                    <p class="mt-1 text-sm text-gray-500">Ayo mulai belanja di Gadget Official!</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" {{-- Ganti 'home' dengan route halaman belanja utama Anda --}}
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- Layout Dua Kolom untuk Keranjang Isi --}}
            <div class="w-full flex flex-col lg:flex-row gap-8 lg:gap-10">
                {{-- Kolom Kiri: Daftar Item Keranjang & Form Alamat --}}
                <div class="w-full lg:w-2/3 flex flex-col gap-8">
                    {{-- Daftar Item Keranjang --}}
                    <div id="cart-items" class="w-full flex flex-col gap-5">
                        @foreach ($cartItems as $item)
                            {{-- Diubah dari forelse karena @if sudah menangani empty --}}
                            <div wire:key="cart-item-{{ $item->id }}"
                                class="product-cart-item bg-white flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 sm:p-5 rounded-xl sm:rounded-[20px] border border-[#E5E5E5] shadow">
                                {{-- Info Produk & Gambar --}}
                                <div class="flex items-center gap-3 sm:gap-5 w-full sm:w-auto mb-4 sm:mb-0 flex-grow">
                                    <div
                                        class="w-20 h-20 sm:w-[100px] sm:h-[70px] flex shrink-0 overflow-hidden items-center justify-center bg-gray-100 rounded-md">
                                        @if ($item->product && $item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                                        @else
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex flex-col gap-0.5 flex-grow">
                                        <p
                                            class="font-semibold text-black leading-tight sm:leading-[22px] text-sm sm:text-base">
                                            {{ optional($item->product)->name }}</p>
                                        <p class="font-medium text-sm text-[#0D5CD7]">Rp
                                            {{ number_format(optional($item->product)->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- Kuantitas --}}
                                <div
                                    class="flex items-center gap-2 sm:gap-3 my-3 sm:my-0 sm:w-auto justify-start sm:justify-center mx-auto sm:mx-0">
                                    <button wire:click="decrementQuantity({{ $item->id }})"
                                        class="w-7 h-7 flex shrink-0 items-center justify-center text-gray-600 hover:text-red-500 disabled:opacity-50"
                                        @if ($item->quantity <= 1) disabled @endif
                                        aria-label="Kurangi kuantitas">
                                        <img src="{{ asset('assets/images/icons/minus-cirlce.svg') }}" alt="minus"
                                            class="w-full h-full">
                                    </button>
                                    <input type="number" value="{{ $item->quantity }}" min="1"
                                        wire:change="updateQuantity({{ $item->id }}, $event.target.value)"
                                        class="w-12 text-center text-[#0D5CD7] font-semibold bg-transparent border-0 p-0 focus:ring-0 appearance-none [-moz-appearance:_textfield]">
                                    <button wire:click="incrementQuantity({{ $item->id }})"
                                        class="w-7 h-7 flex shrink-0 items-center justify-center text-gray-600 hover:text-green-500"
                                        aria-label="Tambah kuantitas">
                                        <img src="{{ asset('assets/images/icons/add-circle.svg') }}" alt="plus"
                                            class="w-full h-full">
                                    </button>
                                </div>

                                {{-- Total per Item & Tombol Hapus --}}
                                <div
                                    class="flex flex-col sm:flex-row items-end sm:items-center gap-3 sm:gap-5 w-full sm:w-auto mt-3 sm:mt-0">
                                    <div class="flex flex-col gap-0.5 text-left sm:text-right w-full sm:w-[130px]">
                                        <p class="text-xs sm:text-sm text-[#616369]">Total</p>
                                        <p
                                            class="font-semibold text-[#0D5CD7] leading-tight sm:leading-[22px] text-sm sm:text-base">
                                            Rp
                                            {{ number_format(optional($item->product)->price * $item->quantity, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <button wire:click="removeItem({{ $item->id }})" title="Hapus item"
                                        class="p-2 sm:p-2.5 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors self-end sm:self-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach {{-- Diubah dari forelse --}}
                    </div>

                    {{-- Form Alamat Pengiriman --}}
                    <div class="w-full flex flex-col shrink-0 gap-4 h-fit mt-0 sm:mt-8">
                        <h2 class="font-bold text-2xl leading-[34px] text-black">Alamat Pengiriman</h2>
                        <div
                            class="flex flex-col gap-5 p-6 sm:p-[30px] rounded-xl sm:rounded-3xl border border-[#E5E5E5] bg-white shadow">
                            <div>
                                <label for="shippingName" class="block mb-1 text-sm font-medium text-gray-700">Nama
                                    Lengkap Penerima <span class="text-red-500">*</span></label>
                                <div
                                    class="flex items-center gap-3 rounded-full border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('shippingName') border-red-500 ring-red-500 @enderror">
                                    <div class="flex shrink-0">
                                        <img src="{{ asset('assets/images/icons/profile-circle.svg') }}"
                                            alt="Nama Icon" class="w-5 h-5 sm:w-6 sm:h-6">
                                    </div>
                                    <input type="text" id="shippingName" wire:model.defer="shippingName"
                                        class="appearance-none outline-none w-full placeholder:text-[#616369] font-semibold text-black text-sm sm:text-base bg-transparent border-0 focus:ring-0"
                                        placeholder="Nama Lengkap Penerima">
                                </div>
                                @error('shippingName')
                                    <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="shippingAddress"
                                    class="block mb-1 text-sm font-medium text-gray-700">Alamat Lengkap <span
                                        class="text-red-500">*</span></label>
                                <div
                                    class="flex items-start gap-3 rounded-xl sm:rounded-[20px] border border-[#E5E5E5] p-3 sm:p-4 focus-within:ring-2 focus-within:ring-[#FFC736] transition-all duration-300 @error('shippingAddress') border-red-500 ring-red-500 @enderror">
                                    <div class="flex shrink-0 pt-1">
                                        <img src="{{ asset('assets/images/icons/house-2.svg') }}" alt="Alamat Icon"
                                            class="w-5 h-5 sm:w-6 sm:h-6">
                                    </div>
                                    <textarea id="shippingAddress" wire:model.defer="shippingAddress" rows="5"
                                        class="appearance-none outline-none w-full placeholder:text-[#616369] font-semibold text-black text-sm sm:text-base bg-transparent border-0 focus:ring-0 resize-none"
                                        placeholder="Alamat Lengkap Pengiriman (Nama Jalan, No Rumah, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos)"></textarea>
                                </div>
                                @error('shippingAddress')
                                    <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Pembayaran --}}
                <div class="w-full lg:w-1/3 flex flex-col shrink-0 gap-4 h-fit mt-8 lg:mt-0">
                    <h2 class="font-bold text-2xl leading-[34px] text-black">Detail Pembayaran</h2>
                    <div
                        class="w-full bg-white border border-[#E5E5E5] flex flex-col gap-5 sm:gap-6 p-6 sm:p-[30px] rounded-xl sm:rounded-3xl shadow">
                        {{-- ... (Konten Detail Pembayaran seperti sebelumnya) ... --}}
                        <div class="flex flex-col gap-3 sm:gap-4 border-b border-gray-200 pb-4">
                            <div class="flex items-center justify-between">
                                <p class="text-black">Subtotal Produk</p>
                                <p class="font-semibold text-black">Rp {{ number_format($subTotal, 0, ',', '.') }}</p>
                            </div>
                            @if ($paymentMethod === 'installment' && $interestAmount > 0)
                                <div class="flex items-center justify-between text-green-600">
                                    <p>Bunga (5%)</p>
                                    <p class="font-semibold">Rp {{ number_format($interestAmount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col gap-1 mb-4">
                            <p class="font-semibold text-black text-lg">Grand Total</p>
                            <p
                                class="font-bold text-2xl sm:text-[32px] leading-tight sm:leading-[48px] text-[#0D5CD7]">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-3">
                            <label for="payment_method" class="font-semibold text-black">Metode Pembayaran</label>
                            <select wire:model.live="paymentMethod" id="payment_method"
                                class="appearance-none outline-none w-full p-3 rounded-lg border border-[#E5E5E5] focus:ring-[#FFC736] focus:border-[#FFC736] bg-white text-black">
                                <option value="cash">Bayar Tunai / Transfer</option>
                                <option value="installment">Cicilan</option>
                            </select>
                            @error('paymentMethod')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($paymentMethod === 'installment')
                            <div wire:key="installment-options"
                                class="flex flex-col gap-3 mt-3 transition-all duration-300 ease-in-out">
                                <label for="installment_plan" class="font-semibold text-black">Pilih Tenor
                                    Cicilan</label>
                                <select wire:model.live="installmentPlan" id="installment_plan"
                                    class="appearance-none outline-none w-full p-3 rounded-lg border border-[#E5E5E5] focus:ring-[#FFC736] focus:border-[#FFC736] bg-white text-black">
                                    <option value="">-- Pilih Tenor --</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                </select>
                                @error('installmentPlan')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror

                                @if (!empty($installmentDetails) && $installmentPlan)
                                    <div class="mt-4 border-t border-gray-200 pt-4">
                                        <p class="font-semibold text-black mb-2">Rincian Cicilan:</p>
                                        <ul class="space-y-1 text-sm text-gray-700">
                                            @foreach ($installmentDetails as $index => $detail)
                                                <li class="flex justify-between">
                                                    <span>Cicilan ke-{{ $index + 1 }}
                                                        ({{ $detail['due_date']->format('d M Y') }})
                                                    </span>
                                                    <span class="font-medium">Rp
                                                        {{ number_format($detail['amount'], 0, ',', '.') }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <p class="text-xs text-gray-500 mt-2">Total cicilan: {{ $installmentPlan }}
                                            bulan.</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="flex flex-col gap-3 mt-6">
                            <button wire:click="placeOrder"
                                class="w-full p-3 sm:p-[12px_24px] bg-[#0D5CD7] rounded-full text-center font-semibold text-white text-base hover:bg-blue-700 transition-colors">
                                <span wire:target="placeOrder">Checkout Sekarang</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
