<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        {{-- Judul Halaman dan Tombol Ekspor --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Data Pengembalian</h1>
            <button wire:click="exportPdf" type="button"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-700 dark:focus:ring-red-900 disabled:opacity-50 whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Ekspor PDF
            </button>
        </div>

        {{-- Kontainer Tabel --}}
        <div
            class="relative hide-scrollbar overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-10">No.</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Tanggal Transaksi</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Kode Transaksi</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Nama Pembeli</th>
                        <th scope="col" class="px-6 py-3 whitespace-nowrap">Nama Produk</th>
                        <th scope="col" class="px-6 py-3 text-center">Jumlah</th>
                        <th scope="col" class="px-6 py-3 text-right whitespace-nowrap">Harga Satuan</th>
                        <th scope="col" class="px-6 py-3 text-right whitespace-nowrap">Total Retur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returnedItems as $index => $item)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $returnedItems->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap text-center">
                                {{ optional($item->order)->created_at->locale('id')->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ optional($item->order)->order_code }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                                {{ optional($item->order->user)->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                                {{ optional($item->product)->name ?? 'Produk Dihapus' }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-900 dark:text-white">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-900 border-b dark:border-gray-700">
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data pengembalian ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if ($returnedItems->hasPages())
            <div class="mt-6">
                {{ $returnedItems->links() }}
            </div>
        @endif
    </div>
</div>
