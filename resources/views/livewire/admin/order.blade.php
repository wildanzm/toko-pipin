<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        {{-- Judul Halaman --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Data Pesanan</h1>
        </div>

        {{-- Notifikasi --}}
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
                <label for="order_search_input" class="sr-only">Cari</label>
                <input wire:model.live.debounce.300ms="search" type="search" id="order_search_input"
                    class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Cari kode, nama pembeli, produk...">
            </div>
            <div>
                <label for="filter_status_order"
                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Filter Status
                    Pesanan:</label>
                <select wire:model.live="filterStatus" id="filter_status_order"
                    class="block w-full p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="pending">Belum Dibayar</option>
                    <option value="paid">Sudah Dibayar</option>
                    <option value="processing">Diproses</option>
                    <option value="shipped">Dikirim</option>
                    <option value="delivered">Diterima Pembeli</option>
                    <option value="completed">Selesai</option>
                    <option value="return_requested">Pengajuan Pengembalian</option>
                    <option value="awaiting_return">Menunggu Barang Kembali</option>
                    <option value="returned">Dikembalikan</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
        </div>

        {{-- Kontainer Tabel --}}
        <div
            class="relative overflow-x-auto hide-scrollbar shadow-md sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 sm:px-6 w-10">No.</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 whitespace-nowrap">Kode Transaksi</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 whitespace-nowrap">Nama Pembeli</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 whitespace-nowrap">Nama Produk</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 text-center">Jumlah</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 text-right whitespace-nowrap">Harga Satuan</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 text-right whitespace-nowrap">Total Harga Item</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 text-center whitespace-nowrap">Status Pesanan</th>
                        <th scope="col" class="px-4 py-3 sm:px-6">Alamat</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 whitespace-nowrap">Pembayaran</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 text-center whitespace-nowrap">Tgl. Pembelian</th>
                        <th scope="col" class="px-4 py-3 sm:px-6 text-center min-w-[150px]">Aksi</th>
                        {{-- Min-width untuk kolom Aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orderItems as $index => $item)
                        <tr
                            class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            {{-- ... (Kolom data lainnya tetap sama) ... --}}
                            <td class="px-4 py-4 sm:px-6 font-medium text-gray-900 dark:text-white">
                                {{ $orderItems->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $item->order->order_code }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $item->order->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $item->product->name ?? 'Produk Dihapus' }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-center text-gray-900 dark:text-white">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-right text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-right text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-center whitespace-nowrap">
                                @php $status = strtolower($item->order->status); @endphp
                                @if ($status == 'pending')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-200">Belum
                                        Dibayar</span>
                                @elseif(in_array($status, ['paid', 'processing']))
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-200">Diproses</span>
                                @elseif($status == 'shipped')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-200">Dikirim</span>
                                @elseif($status == 'delivered')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-teal-800 bg-teal-100 rounded-full dark:bg-teal-900 dark:text-teal-200">Diterima
                                        Pembeli</span>
                                @elseif($status == 'completed')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-200">Selesai</span>
                                @elseif($status == 'return_requested')
                                    {{-- Status untuk pengajuan dari user --}}
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full dark:bg-orange-900 dark:text-orange-200">Pengajuan
                                        Retur</span>
                                @elseif($status == 'awaiting_return')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full dark:bg-orange-900 dark:text-orange-200">Menunggu
                                        Barang Kembali</span>
                                @elseif($status == 'returned')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-300 rounded-full dark:bg-gray-600 dark:text-gray-200">Dikembalikan</span>
                                @elseif($status == 'cancelled')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-300 rounded-full dark:bg-gray-600 dark:text-gray-200">Dibatalkan</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full dark:bg-gray-900 dark:text-gray-300">{{ Str::ucfirst($item->order->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-gray-900 dark:text-white text-xs max-w-[150px] truncate"
                                title="{{ $item->order->shipping_address }}">
                                {{ Str::limit($item->order->shipping_address, 30) }}
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-gray-900 dark:text-white whitespace-nowrap">
                                @if (strtolower($item->order->payment_method) == 'cash')
                                    Tunai
                                @elseif (strtolower($item->order->payment_method) == 'installment')
                                    Cicil @if ($item->order->installment_plan)
                                        ({{ $item->order->installment_plan }})
                                    @endif
                                @else
                                    {{ Str::ucfirst($item->order->payment_method) }}
                                @endif
                            </td>
                            <td class="px-4 py-4 sm:px-6 text-center text-gray-900 dark:text-white whitespace-nowrap">
                                {{ $item->order->created_at->locale('id')->translatedFormat('d F Y') }}
                            </td>

                            {{-- Kolom Aksi yang Diperbarui untuk Admin --}}
                            <td
                                class="px-4 py-4 sm:px-6 text-center whitespace-nowrap space-y-1 sm:space-y-0 sm:space-x-1">
                                @if ($item->order)
                                    @php $status = strtolower($item->order->status); @endphp

                                    @if ($status === 'pending')
                                        <button wire:click="markAsPaid({{ $item->order->id }})"
                                            title="Tandai Sudah Dibayar" class="btn-action-primary">
                                            {{-- Menggunakan class generik untuk styling --}}
                                            Tandai Lunas
                                        </button>
                                    @elseif(in_array($status, ['paid', 'processing']))
                                        <button wire:click="markAsShipped({{ $item->order->id }})"
                                            title="Tandai Sudah Dikirim" class="btn-action-yellow">
                                            Kirim Pesanan
                                        </button>
                                    @elseif($status === 'shipped')
                                        <span class="text-xs text-gray-500 dark:text-gray-400 italic">Menunggu
                                            Konfirmasi User</span>
                                    @elseif($status === 'delivered')
                                        <button wire:click="confirmOrderCompleted({{ $item->order->id }})"
                                            title="Selesaikan Pesanan (Admin)" class="btn-action-green">
                                            Selesaikan
                                        </button>
                                    @elseif($status === 'return_requested')
                                        {{-- Admin melihat pengajuan dari user --}}
                                        <button wire:click="approveReturnRequest({{ $item->order->id }})"
                                            title="Setujui Pengajuan Pengembalian" class="btn-action-green">
                                            Setujui Retur
                                        </button>
                                        <button
                                            wire:click="processReturnRequest({{ $item->order->id }}, 'reject_return_request')"
                                            title="Tolak Pengajuan Pengembalian" class="btn-action-danger mt-1 sm:mt-0">
                                            {{-- Tombol Tolak (opsional) --}}
                                            Tolak Retur
                                        </button>
                                    @elseif($status === 'awaiting_return')
                                        {{-- Admin menunggu barang fisik kembali --}}
                                        <button
                                            wire:click="processReturnRequest({{ $item->order->id }}, 'mark_returned')"
                                            title="Konfirmasi Barang Pengembalian Diterima" class="btn-action-green">
                                            Barang Retur Diterima
                                        </button>
                                    @elseif(in_array($status, ['completed', 'returned', 'cancelled']))
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 italic">{{ Str::ucfirst($status) }}</span>
                                    @else
                                        <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                            <td colspan="12" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data pesanan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if ($orderItems->hasPages())
            <div class="mt-6">
                {{ $orderItems->links() }}
            </div>
        @endif
    </div>
</div>
