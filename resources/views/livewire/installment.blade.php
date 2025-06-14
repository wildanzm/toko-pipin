<div>
    <div class="container max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Judul Halaman --}}
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Daftar Tagihan Kredit</h1>
        </div>

        {{-- Daftar Kartu Tagihan --}}
        <div class="space-y-6">
            @forelse ($orders as $order)
                <div wire:key="order-installment-{{ $order->id }}"
                    class="bg-white shadow-md rounded-xl border border-gray-200 overflow-hidden">
                    {{-- Header Kartu: Info Pesanan --}}
                    <div
                        class="bg-gray-50 p-4 sm:p-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start gap-3">
                        <div>
                            <p class="text-xs text-gray-500">Kode Transaksi</p>
                            <p class="font-semibold text-blue-600">{{ $order->order_code }}</p>
                        </div>
                        <div class="sm:text-right">
                            <p class="text-xs text-gray-500">Tanggal Pembelian</p>
                            <p class="font-medium text-gray-700">
                                {{ $order->created_at->locale('id')->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    {{-- Isi Kartu --}}
                    <div class="p-4 sm:p-5">
                        {{-- Daftar Produk --}}
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-800 mb-2">Produk yang Dibeli:</p>
                            <div class="text-sm text-gray-600 space-y-1">
                                @foreach ($order->items as $item)
                                    <span>{{ optional($item->product)->name ?? 'Produk Dihapus' }}
                                        ({{ $item->quantity }}x)
                                    </span>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <hr class="my-4 border-gray-200">

                        {{-- Rincian Tagihan Cicilan --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-800 mb-2">Rincian Tagihan:</p>
                            <div class="space-y-3">
                                @foreach ($order->installments as $installment)
                                    <div
                                        class="p-3 rounded-lg border {{ $installment->is_paid ? 'bg-green-50 border-green-200' : (now()->gt($installment->due_date) ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200') }}">
                                        <div
                                            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                            <div class="flex-grow">
                                                <p
                                                    class="font-medium {{ $installment->is_paid ? 'text-green-800' : (now()->gt($installment->due_date) ? 'text-red-800' : 'text-gray-800') }}">
                                                    Cicilan ke-{{ $loop->iteration }} /
                                                    {{ $order->installments->count() }}: Rp
                                                    {{ number_format($installment->amount, 0, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Jatuh Tempo:
                                                    {{ \Carbon\Carbon::parse($installment->due_date)->locale('id')->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                            <div class="shrink-0 mt-2 sm:mt-0 text-right">
                                                @if ($installment->is_paid)
                                                    <span
                                                        class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">LUNAS</span>
                                                @else
                                                    {{-- Tampilkan badge TERLAMBAT atau BELUM LUNAS --}}
                                                    <span
                                                        class="px-3 py-1 text-xs font-bold {{ now()->gt($installment->due_date) ? 'bg-red-500 text-white' : 'bg-yellow-400 text-yellow-900' }} rounded-full">
                                                        {{ now()->gt($installment->due_date) ? 'TERLAMBAT' : 'BELUM LUNAS' }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- PERBAIKAN: Tampilkan Detail Keterlambatan dan Denda --}}
                                        @if ($installment->late_days > 0 && !$installment->is_paid)
                                            <div class="mt-3 pt-3 border-t border-red-200/50">
                                                <p class="text-xs font-semibold text-red-700">
                                                    <span class="font-bold">Terlambat {{ $installment->late_days }}
                                                        hari!</span>
                                                </p>
                                                <p class="text-xs text-red-600">
                                                    Denda Keterlambatan (0.1%/hari): <strong>Rp
                                                        {{ number_format($installment->late_fee, 0, ',', '.') }}</strong>
                                                </p>
                                                <p class="text-sm font-bold text-gray-800 mt-2">
                                                    Total yang harus dibayar: <strong class="text-red-600">Rp
                                                        {{ number_format($installment->amount + $installment->late_fee, 0, ',', '.') }}</strong>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-xl shadow border border-gray-200">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak Ada Tagihan Cicilan</h3>
                    <p class="mt-1 text-sm text-gray-500">Anda tidak memiliki transaksi dengan metode pembayaran
                        cicilan.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        @if ($orders->hasPages())
            <div class="mt-8">
                {{-- PERBAIKAN: Paginasi sudah otomatis menggunakan styling default Tailwind untuk mode terang --}}
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
