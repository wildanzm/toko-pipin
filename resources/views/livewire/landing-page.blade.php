<div>
    <div class="bg-[#EFF3FA] pt-[5px] pb-12 md:pb-[50px]">
        <div
            class="container max-w-[1130px] mx-auto px-4 sm:px-6 flex flex-col lg:flex-row items-center lg:justify-between gap-8 lg:gap-12 mt-10 md:mt-[50px]">

            {{-- Kolom Teks (Kiri di Desktop, Atas di Mobile) --}}
            <div
                class="flex flex-col gap-6 md:gap-[30px] text-center lg:text-left w-full lg:w-1/2 xl:w-2/5 order-2 lg:order-1">
                <div class="flex justify-center lg:justify-start">
                    <div class="flex items-center gap-[10px] p-[8px_16px] rounded-full bg-white w-fit shadow-md">
                        <div class="w-[22px] h-[22px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/crown.svg') }}" alt="Ikon Mahkota">
                        </div>
                        <p class="font-semibold text-black text-sm">Produk Ke-100 Terpopuler di Gadget Official!</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3 md:gap-[14px]">
                    <h1
                        class="font-bold text-black text-4xl sm:text-5xl lg:text-[55px] leading-tight sm:leading-tight lg:leading-[55px]">
                        Temukan Gadget Impianmu Disini!
                    </h1>
                    <p
                        class="text-base sm:text-lg leading-relaxed sm:leading-[34px] text-[#6A7789] max-w-lg mx-auto lg:mx-0">
                        Nikmati fitur super canggih dengan integrasi AI dari Gadget Official, lebih kaya dari platform
                        lain untuk semua perangkat Anda.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4">
                    <a href="#" {{-- Ganti dengan route('cart.add', ['product_id' => ID_PRODUK]) --}}
                        class="w-full sm:w-auto p-4 sm:p-[18px_24px] rounded-full font-semibold bg-[#0D5CD7] text-white text-center text-sm sm:text-base hover:bg-blue-700 transition-colors">
                        Belanja
                    </a>
                    <a href="#" {{-- Ganti dengan route('product.details', ['product_slug' => SLUG_PRODUK]) --}}
                        class="w-full sm:w-auto p-4 sm:p-[18px_24px] text-black rounded-full font-semibold bg-white text-center text-sm sm:text-base shadow-md hover:bg-gray-50 transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>

            {{-- Kolom Gambar (Kanan di Desktop, Bawah di Mobile) --}}
            <div
                class="w-full max-w-lg mx-auto lg:w-1/2 xl:w-3/5 lg:max-w-none lg:mx-0 h-auto lg:h-[450px] {{-- Tinggi untuk desktop dinaikkan dari 400px ke 450px --}}
            flex items-center justify-center shrink-0 relative mt-8 lg:mt-0 order-1 lg:order-2">
                <img src="{{ asset('assets/images/banners/mba13-m2-digitalmat-gallery-1-202402-Photoroom 2.png') }}"
                    class="max-h-[300px] sm:max-h-[360px] lg:max-h-[450px] object-contain" {{-- Nilai max-h dinaikkan --}}
                    alt="iPhone 15 Pro Terbaru di Gadget Official">

                {{-- Badge Bonus --}}
                <div
                    class="absolute 
                bottom-2 left-2 xs:bottom-4 xs:left-4 
                sm:top-[60%] sm:bottom-auto sm:left-4 md:left-6
                bg-white p-2.5 px-3 sm:p-[14px_16px] rounded-2xl sm:rounded-3xl 
                flex items-center gap-2 sm:gap-[10px] shadow-lg transition-all hover:scale-105">
                    <div
                        class="w-8 h-8 sm:w-12 sm:h-12 flex shrink-0 rounded-full items-center justify-center bg-[#FFC736] overflow-hidden">
                        <img src="{{ asset('assets/images/icons/code-circle.svg') }}" class="w-4 h-4 sm:w-6 sm:h-6"
                            alt="Ikon Kode Bonus">
                    </div>
                    <p class="font-semibold text-black text-xs sm:text-sm leading-tight">Bonus Eksklusif</p>
                </div>

                {{-- Badge Garansi --}}
                <div
                    class="absolute
                top-2 right-2 xs:top-4 xs:right-4
                sm:top-[30%] sm:bottom-auto sm:right-4 md:right-6
                bg-white p-2.5 px-3 sm:p-[14px_16px] rounded-2xl sm:rounded-3xl
                flex flex-col items-center text-center gap-1 sm:gap-[10px] shadow-lg transition-all hover:scale-105">
                    <div
                        class="w-8 h-8 sm:w-12 sm:h-12 flex shrink-0 rounded-full items-center justify-center bg-[#FFC736] overflow-hidden">
                        <img src="{{ asset('assets/images/icons/star-outline.svg') }}" class="w-4 h-4 sm:w-6 sm:h-6"
                            alt="Ikon Garansi Bintang">
                    </div>
                    <p class="font-semibold text-black text-xs sm:text-sm leading-tight">Garansi Resmi</p>
                </div>
            </div>
        </div>
    </div>
    <section id="content"
        class="container max-w-[1130px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-10 sm:gap-[50px] pt-10 sm:pt-[50px] pb-16 sm:pb-[100px]">
        <div id="new-release" class="flex flex-col gap-6 sm:gap-[30px]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                <h2 class="font-bold text-black text-xl sm:text-2xl leading-tight sm:leading-[34px]">Produk Terbaru dari
                    Gadget Official</h2>
            </div>

            @if (session()->has('cart_message'))
                <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg" x-data="{ show: true }"
                    x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    {{ session('cart_message') }}
                </div>
            @endif

            @if (session()->has('cart_error'))
                <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg" x-data="{ show: true }" x-show="show"
                    x-init="setTimeout(() => show = false, 3000)" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    {{ session('cart_error') }}
                </div>
            @endif
            {{-- Grid Produk Responsif --}}
            <div
                class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 xs:gap-4 sm:gap-6 md:gap-[30px]">

                @forelse ($products as $product)
                    <div class="product-card-wrapper group flex flex-col"> {{-- Wrapper untuk kartu --}}
                        <div
                            class="bg-white flex flex-col gap-3 sm:gap-4 p-3 sm:p-4 md:p-5 rounded-[20px] ring-1 ring-[#E5E5E5] group-hover:ring-2 group-hover:ring-[#FFC736] transition-all duration-300 w-full h-full shadow hover:shadow-xl">
                            {{-- Link ke detail produk menggunakan slug produk --}}
                            <a href="" {{-- Ganti 'product.show' dengan nama route Anda --}} class="block">
                                <div
                                    class="w-full h-[120px] xs:h-[140px] sm:h-[160px] flex shrink-0 items-center justify-center overflow-hidden rounded-lg mb-2 sm:mb-0">
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}" {{-- Menampilkan gambar dari storage --}}
                                            class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
                                            alt="{{ $product->name }}">
                                    @else
                                        {{-- Placeholder jika tidak ada gambar --}}
                                        <div
                                            class="w-full h-full bg-gray-200 flex items-center justify-center rounded-lg">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="flex flex-col gap-1 sm:gap-2 flex-grow">
                                <div class="flex flex-col gap-0.5 sm:gap-1">
                                    {{-- Link ke detail produk menggunakan slug produk --}}
                                    <a href="" class="hover:underline">
                                        <h3 class="font-semibold text-black leading-tight sm:leading-[22px] text-sm sm:text-base truncate"
                                            title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    {{-- Bagian Kategori Dihapus --}}
                                    {{-- <p class="text-xs sm:text-sm text-[#616369]">Kategori Produk</p> --}}
                                </div>
                                <p
                                    class="font-semibold text-[#0D5CD7] text-sm sm:text-base leading-tight sm:leading-[22px] mt-auto">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Tombol Tambah ke Keranjang --}}
                            <button type="button" wire:click="addToCart({{ $product->id }})"
                                class="w-full mt-2 sm:mt-3 py-2 px-3 sm:py-2.5 sm:px-4 bg-[#0D5CD7] text-white text-xs sm:text-sm font-semibold rounded-full
                                       hover:bg-blue-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50
                                       disabled:opacity-70 whitespace-nowrap text-center">
                                Masuk Keranjang
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">Oops! Belum ada produk untuk ditampilkan.
                        </p>
                        {{-- Anda bisa menambahkan link untuk kembali atau ke halaman lain --}}
                        {{-- <a href="{{ route('home') }}" class="mt-4 inline-block text-blue-600 hover:underline">Kembali ke Beranda</a> --}}
                    </div>
                @endforelse
            </div>

            {{-- Paginasi (jika menggunakan ->paginate() di komponen) --}}
            @if ($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
