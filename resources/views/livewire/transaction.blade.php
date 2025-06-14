<div>
    <div class="container max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Daftar Transaksi</h1>
        </div>

        {{-- Notifikasi Flash Messages --}}
        @if (session()->has('info'))
            <div class="mb-6 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert">
                {{ session('info') }}
            </div>
        @endif
        @if (session()->has('message'))
            <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Daftar Kartu Transaksi --}}
        <div class="space-y-6">
            @forelse ($orders as $order)
                <div wire:key="order-{{ $order->id }}"
                    class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                    {{-- Header Kartu: Kode Transaksi, Tanggal, Status --}}
                    <div
                        class="bg-gray-50 p-4 sm:p-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        {{-- ... (Info Kode Transaksi & Tanggal Pembelian tetap sama) ... --}}
                        <div>
                            <p class="text-sm text-gray-600">Kode Transaksi:</p>
                            <p class="font-semibold text-gray-800 text-base sm:text-lg">{{ $order->order_code }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-sm text-gray-600">Tanggal Pembelian:</p>
                            <p class="font-medium text-gray-700 text-sm lg:text-center text-left ">
                                {{ $order->created_at->locale('id')->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div class="w-full sm:w-auto mt-2 sm:mt-0 text-left sm:text-right">
                            <p class="text-sm text-gray-600">Status Pesanan:</p>
                            @php $status = strtolower($order->status); @endphp
                            @if ($status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Belum
                                    Dibayar</span>
                            @elseif(in_array($status, ['paid', 'processing']))
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Diproses</span>
                            @elseif($status == 'shipped')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Dikirim</span>
                            @elseif($status == 'delivered')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-teal-800 bg-teal-100 rounded-full">Sudah
                                    Diterima</span>
                            @elseif($status == 'completed')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Selesai</span>
                            @elseif($status == 'return_requested')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">Pengajuan
                                    Pengembalian</span>
                            @elseif($status == 'awaiting_return')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">Menunggu
                                    Barang Kembali</span>
                            @elseif($status == 'returned')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-300 rounded-full">Dikembalikan</span>
                            @elseif($status == 'cancelled' || $status == 'failed')
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">{{ Str::ucfirst($order->status) }}</span>
                            @else
                                <span
                                    class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">{{ Str::ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Item Produk dalam Transaksi --}}
                    <div class="p-4 sm:p-5 space-y-4">
                        @foreach ($order->items as $item)
                            <div wire:key="order-{{ $order->id }}-item-{{ $item->id }}"
                                class="flex flex-col sm:flex-row gap-4 items-start pb-4 @if (!$loop->last) border-b border-gray-100 @endif">
                                {{-- ... (Detail Item Produk tetap sama seperti sebelumnya) ... --}}
                                <div
                                    class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-md flex items-center justify-center overflow-hidden shrink-0">
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
                                <div class="flex-grow">
                                    <h3 class="font-semibold text-gray-800 text-base leading-tight">
                                        {{ optional($item->product)->name ?? 'Produk Tidak Tersedia' }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $item->quantity }} barang x Rp
                                        {{ number_format($item->price, 0, ',', '.') }}</p>
                                    <div class="mt-1">
                                        <p class="text-xs text-gray-500">Harga Item</p>
                                        <p class="font-semibold text-gray-800 text-base">Rp
                                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Informasi Pembayaran & Aksi --}}
                    <div
                        class="bg-gray-50 p-4 sm:p-5 border-t border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                        <div class="flex-grow space-y-1 mb-4 md:mb-0">
                            <div>
                                <span class="text-sm text-gray-600">Metode Pembayaran:</span>
                                <span class="font-semibold text-gray-800 ml-1">
                                    @if (strtolower($order->payment_method) == 'cash')
                                        Tunai / Transfer
                                    @elseif (strtolower($order->payment_method) == 'installment')
                                        Cicilan @if ($order->installment_plan)
                                            ({{ $order->installment_plan }})
                                        @endif
                                    @else
                                        {{ Str::ucfirst($order->payment_method) }}
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Total Pesanan:</span>
                                <span class="font-bold text-lg text-blue-600 ml-1">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Tombol Aksi Dinamis --}}
                        <div
                            class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto justify-end items-stretch sm:items-center self-start md:self-end">
                            <button wire:click="streamUserInvoice({{ $order->id }})" type="button"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-center text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-8a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Invoice
                            </button>

                            @php $currentStatus = strtolower($order->status); @endphp

                            @if ($currentStatus == 'shipped' || $currentStatus == 'delivered')
                                <button wire:click="confirmOrderReceived({{ $order->id }})" type="button"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-center text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 whitespace-nowrap">
                                    Pesanan Selesai
                                </button>
                            @endif

                            {{-- Tombol Ajukan Pengembalian muncul jika statusnya shipped, delivered, atau completed TAPI BELUM dalam proses retur --}}
                            @if (in_array($currentStatus, ['shipped', 'delivered']) &&
                                    !in_array($currentStatus, ['return_requested', 'awaiting_return', 'returned']))
                                <button wire:click="requestReturn({{ $order->id }})" type="button"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-center text-white bg-orange-500 rounded-lg hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 whitespace-nowrap">
                                    Ajukan Pengembalian
                                </button>
                            @endif

                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full flex justify-center">
                    <div class="text-center py-12 bg-white rounded-xl shadow border border-gray-200 max-w-md w-full">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Belum Ada Transaksi</h3>
                        <p class="mt-1 text-sm text-gray-500">Anda belum melakukan transaksi apapun.</p>
                        <div class="mt-6">
                            <a href="{{-- route('shop.index') --}}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
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
