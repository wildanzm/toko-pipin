<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        {{-- Judul Halaman --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Daftar Tagihan Kredit</h1>
            {{-- Tambahkan tombol filter atau ekspor global di sini jika perlu --}}
        </div>

        @if (session()->has('message'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300"
                role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter dan Pencarian --}}
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="order_search" class="sr-only">Cari</label>
                <input wire:model.live.debounce.300ms="search" type="search" id="order_search"
                    class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Cari kode transaksi atau nama pembeli...">
            </div>
            <div>
                <label for="filterStatus" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Filter
                    Status Cicilan:</label>
                <select wire:model.live="filterStatus" id="filterStatus"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua</option>
                    <option value="pending_installments">Ada Cicilan Belum Lunas</option>
                    <option value="fully_paid">Semua Cicilan Lunas</option>
                </select>
            </div>
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

        {{-- Daftar Kartu Transaksi (Pesanan) --}}
        <div class="space-y-6">
            @forelse ($orders as $order)
                <div wire:key="order-group-{{ $order->id }}"
                    class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    {{-- Header Informasi Pesanan Utama --}}
                    <div
                        class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kode Transaksi</p>
                                <p class="font-semibold text-lg text-blue-600 dark:text-blue-400">
                                    {{ $order->order_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Pembeli</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->user->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pembelian</p>
                                <p class="font-medium text-gray-800 text-left lg:text-center dark:text-white">
                                    {{ $order->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Metode Pembayaran</p>
                                <p class="font-medium text-gray-800 dark:text-white">
                                    Cicil @if ($order->installment_plan)
                                        ({{ $order->installment_plan }})
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Alamat Pengiriman</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    {{-- Tabel Rincian Produk dan Cicilan untuk Pesanan Ini --}}
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 sm:px-6">Nama Produk</th>
                                    <th scope="col" class="px-4 py-3 sm:px-6 text-center">Jml</th>
                                    <th scope="col" class="px-4 py-3 sm:px-6 text-right">Harga Satuan</th>
                                    <th scope="col" class="px-4 py-3 sm:px-6 text-right">Total Harga Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600/30">
                                        <td
                                            class="px-4 py-3 sm:px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                        <td class="px-4 py-3 sm:px-6 text-center text-gray-700 dark:text-gray-300">
                                            {{ $item->quantity }}</td>
                                        <td
                                            class="px-4 py-3 sm:px-6 text-right text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td
                                            class="px-4 py-3 sm:px-6 text-right font-semibold text-gray-800 dark:text-white whitespace-nowrap">
                                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Daftar Tagihan Cicilan untuk Pesanan Ini --}}
                    @if ($order->installments->isNotEmpty())
                        <div class="p-4 sm:p-6">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-3">Rincian Tagihan
                                Cicilan:</h4>
                            <div class="space-y-3">
                                @foreach ($order->installments as $installment)
                                    <div wire:key="installment-{{ $installment->id }}"
                                        class="p-3 rounded-lg border flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 
                           {{ $installment->is_paid ? 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-700' : (now()->gt($installment->due_date) ? 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-700' : 'bg-gray-50 dark:bg-gray-700/50 border-gray-300 dark:border-gray-600') }}">

                                        <div>
                                            <p
                                                class="text-sm font-medium {{ $installment->is_paid ? 'text-green-700 dark:text-green-300' : (now()->gt($installment->due_date) ? 'text-red-800 dark:text-red-300' : 'text-gray-800 dark:text-gray-200') }}">
                                                Cicilan ke-{{ $loop->iteration }} : Rp
                                                {{ number_format($installment->amount, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                Jatuh Tempo:
                                                {{ \Carbon\Carbon::parse($installment->due_date)->locale('id')->translatedFormat('d F Y') }}
                                            </p>

                                            {{-- PERBAIKAN: Tampilan Informasi Denda yang Lebih Lengkap --}}
                                            @if ($installment->late_days > 0 && !$installment->is_paid)
                                                <div class="mt-2 pt-2 border-t border-red-200 dark:border-red-700/50">
                                                    <p class="text-xs font-semibold text-red-700 dark:text-red-300">
                                                        <span class="font-bold">Terlambat {{ $installment->late_days }}
                                                            hari!</span>
                                                    </p>
                                                    <p class="text-xs text-red-600 dark:text-red-400">
                                                        Denda Keterlambatan (0.1%/hari): <strong>Rp
                                                            {{ number_format($installment->late_fee, 0, ',', '.') }}</strong>
                                                    </p>
                                                    <p class="text-sm font-bold text-gray-800 dark:text-white mt-2">
                                                        Total Tagihan + Denda: <strong
                                                            class="text-red-600 dark:text-red-300">Rp
                                                            {{ number_format($installment->amount + $installment->late_fee, 0, ',', '.') }}</strong>
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="self-center mt-2 sm:mt-0">
                                            @if ($installment->is_paid)
                                                <span
                                                    class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">LUNAS</span>
                                            @else
                                                <button wire:click="markInstallmentAsPaid({{ $installment->id }})"
                                                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
                                                    Tandai Lunas
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Footer Kartu --}}
                    <div
                        class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 text-right">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Keseluruhan Pesanan: </span>
                        <span class="font-bold text-lg text-blue-600 dark:text-blue-400">Rp
                            {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div
                    class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">Belum Ada Tagihan Kredit</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada data tagihan kredit untuk
                        ditampilkan saat ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if ($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
