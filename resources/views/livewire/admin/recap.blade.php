<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        {{-- Judul Halaman dan Tombol Ekspor --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Rekapitulasi Penjualan</h1>
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

        @if (session()->has('info'))
            <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300"
                role="alert">
                {{ session('info') }}
            </div>
        @endif

        {{-- Tabel Rekapitulasi --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Keterangan</th>
                        <th scope="col" class="px-6 py-3 text-center">Jumlah Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-right">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="font-semibold text-gray-800 dark:text-white">
                    {{-- Penjualan Tunai --}}
                    <tr class="bg-gray-100 dark:bg-gray-700/50 border-b border-t dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">Penjualan
                            Tunai</td>
                        <td class="px-6 py-4 text-center">{{ $cashSalesCount }}</td>
                        <td class="px-6 py-4 text-right">Rp{{ number_format($cashSalesTotal, 0, ',', '.') }}</td>
                    </tr>
                    {{-- Penjualan Kredit --}}
                    <tr class="bg-gray-100 dark:bg-gray-700/50 border-b border-t dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">Penjualan
                            Kredit</td>
                        <td class="px-6 py-4 text-center">{{ $creditSalesCount }}</td>
                        <td class="px-6 py-4 text-right">Rp{{ number_format($creditSalesTotal, 0, ',', '.') }}</td>
                    </tr>
                    {{-- Retur Penjualan --}}
                    <tr class="bg-gray-100 dark:bg-gray-700/50 border-b border-t dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">Retur
                            Penjualan</td>
                        <td class="px-6 py-4 text-center">{{ $returnCount }}</td>
                        <td class="px-6 py-4 text-right">
                            Rp{{ number_format($returnTotal, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot class="font-semibold text-gray-800 dark:text-white">
                    {{-- Total Penjualan Kotor --}}
                    <tr class="bg-gray-100 dark:bg-gray-700/50 border-b border-t dark:border-gray-700">
                        <td class="px-6 py-3">Total Penjualan Kotor</td>
                        <td class="px-6 py-3 text-center">{{ $grossSalesCount }}</td>
                        <td class="px-6 py-3 text-right">Rp{{ number_format($grossSalesTotal, 0, ',', '.') }}</td>
                    </tr>
                    {{-- Total Retur --}}
                    <tr class="bg-gray-100 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <td class="px-6 py-3">Total Retur</td>
                        <td class="px-6 py-3 text-center">{{ $returnCount }}</td>
                        <td class="px-6 py-3 text-right">
                            Rp{{ number_format($returnTotal, 0, ',', '.') }}</td>
                    </tr>
                    {{-- Total Penjualan Bersih --}}
                    <tr class="bg-gray-200 dark:bg-gray-600 border-b dark:border-gray-700 text-base">
                        <td class="px-6 py-3">Total Penjualan Bersih</td>
                        <td class="px-6 py-3 text-center">-</td>
                        <td class="px-6 py-3 text-right">Rp{{ number_format($netSalesTotal, 0, ',', '.') }}</td>
                    </tr>
                    {{-- Total Piutang Kredit --}}
                    <tr
                        class="bg-gray-100 dark:bg-gray-700/50 border-b border-t-4 border-white dark:border-t-4 dark:border-gray-800">
                        <td class="px-6 py-3">Total Piutang Kredit</td>
                        <td class="px-6 py-3 text-center">{{ $totalReceivablesCount }}</td>
                        <td class="px-6 py-3 text-right">Rp{{ number_format($totalReceivablesAmount, 0, ',', '.') }}
                        </td>
                    </tr>
                    {{-- Sudah Dibayar --}}
                    <tr class="bg-gray-100 dark:bg-gray-700/50 border-b dark:border-gray-700">
                        <td class="px-6 py-3">Sudah Dibayar</td>
                        <td class="px-6 py-3 text-center">-</td>
                        <td class="px-6 py-3 text-right">Rp{{ number_format($totalPaidReceivables, 0, ',', '.') }}</td>
                    </tr>
                    {{-- Sisa Piutang --}}
                    <tr class="bg-gray-200 dark:bg-gray-600 text-base">
                        <td class="px-6 py-3">Sisa Piutang</td>
                        <td class="px-6 py-3 text-center">-</td>
                        <td class="px-6 py-3 text-right">Rp{{ number_format($remainingReceivables, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
