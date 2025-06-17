<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min grid-cols-1 sm:grid-cols-4 md:grid-cols-2 gap-4">


            {{-- Badge 2: Total Produk (Tema Ungu) --}}
            <div class="bg-purple-500 dark:bg-gray-800 p-5 sm:p-6 rounded-xl shadow-lg flex flex-col">
                <div class="flex items-center justify-between text-purple-100 dark:text-purple-300 mb-2">
                    <h3 class="text-sm font-medium uppercase tracking-wider">Total Pembelian Barang</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-white dark:text-white mb-1">
                    {{ number_format($totalStokBarang, 0, ',', '.') }} Barang
                </p>
                {{-- <p class="text-xs text-purple-50 dark:text-purple-400 opacity-80">Aktif di Tokopipin</p> --}}
            </div>

            {{-- Badge 3: Total Transaksi (Tema Biru Indigo) --}}
            <div class="bg-indigo-500 dark:bg-gray-800 p-5 sm:p-6 rounded-xl shadow-lg flex flex-col">
                <div class="flex items-center justify-between text-indigo-100 dark:text-indigo-300 mb-2">
                    <h3 class="text-sm font-medium uppercase tracking-wider">Total Transaksi Bulan Ini</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-white dark:text-white mb-1">
                    {{ number_format($jumlahTransaksiBarangBulanIni, 0, ',', '.') }} Transaksi
                </p>
            </div>

        </div>
    </div>
</div>
