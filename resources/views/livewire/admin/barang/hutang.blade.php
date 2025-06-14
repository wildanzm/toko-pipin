<div>
    <h1 class="text-3xl font-bold pb-5 dark:text-white">Laporan Hutang Barang</h1>

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.hutang.export') }}"
           class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700 transition">
            Export PDF
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border dark:border-gray-700">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-16 text-center">No</th>
                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                    <th scope="col" class="px-6 py-3 text-center">Toko</th>
                    <th scope="col" class="px-6 py-3 text-center">Jenis Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                    <th scope="col" class="px-6 py-3 text-center">Nominal Hutang</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hutangBarangs as $index => $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">
                            {{ $hutangBarangs->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">{{ $item->nama_barang }}</td>
                        <td class="px-6 py-4 text-center">{{ $item->toko }}</td>
                        <td class="px-6 py-4 text-center capitalize">{{ $item->jenis_pembayaran }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($item->status === 'lunas')
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full">
                                    Lunas
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full">
                                    Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            Rp {{ number_format($item->nominal_hutang, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data hutang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($hutangBarangs->hasPages())
        <div class="mt-6">
            {{ $hutangBarangs->links() }}
        </div>
    @endif
</div>
