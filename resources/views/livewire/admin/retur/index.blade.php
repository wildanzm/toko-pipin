<div>
    <h1 class="text-3xl font-bold pb-5 dark:text-white">Laporan Retur Pembelian</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.retur.export') }}"
            class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700 transition">
            Export PDF
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border dark:border-gray-700">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-16 text-center">No</th>
                    <th scope="col" class="px-6 py-3 text-center">Tanggal Transaksi</th>
                    <th scope="col" class="px-6 py-3 text-center">Kode Barang</th>
                    <th scope="col" class="px-6 py-3">Nama Toko</th>
                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                    <th scope="col" class="px-6 py-3 text-center">Kuantitas</th>
                    <th scope="col" class="px-6 py-3 text-center">Harga Satuan</th>
                    <th scope="col" class="px-6 py-3 text-center">Total Nilai Retur</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ($returBarangs as $index => $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">
                            {{ $returBarangs->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ $item->created_at->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">{{ $item->kode_barang ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item->nama_toko_suplier }}</td>
                        <td class="px-6 py-4">{{ $item->nama_barang }}</td>
                        <td class="px-6 py-4 text-center">{{ $item->kuantitas }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($item->harga_per_satu, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($item->harga_total, 0, ',', '.') }}</td>
                        
                    </tr>
                @empty
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data retur ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($returBarangs->hasPages())
        <div class="mt-6">
            {{ $returBarangs->links() }}
        </div>
    @endif
</div>
