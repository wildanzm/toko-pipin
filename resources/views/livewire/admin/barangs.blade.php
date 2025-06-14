<div>
    <h1 class="text-3xl font-bold pb-5 dark:text-white">Barang</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="relative w-full sm:w-1/2 md:w-1/3 lg:w-80">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" id="table-search"
                class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Cari Barang...">
        </div>

        <button wire:click="showTambahTokoModal"
            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Tambah Toko
        </button>

        <a href="{{ route('admin.barangs.toko') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            Tambah Barang
        </a>
    </div>

    @if ($showTambahTokoModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden"
            x-data="{ showModal: @entangle('showTambahTokoModal') }" x-show="showModal" x-cloak>
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8 w-full max-w-md mx-auto my-8 transform transition-all"
                @click.away="showModal = false">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Tambah Toko</h3>
                    <button type="button" @click="showModal = false"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="simpanToko" class="space-y-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Nama Toko</label>
                        <input type="text" wire:model.defer="namaToko" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
                        @error('namaToko')<span class="text-sm text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700 dark:hover:text-white">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border dark:border-gray-700">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3 w-16">No</th>
                    <th class="px-6 py-3">Nama Barang</th>
                    <th class="px-6 py-3 text-center">Uniq Key</th>
                    <th class="px-6 py-3 text-center">Toko</th>
                    <th class="px-6 py-3 text-center">Harga Beli</th>
                    <th class="px-6 py-3 text-center">Harga Jual</th>
                    <th class="px-6 py-3 text-center">Pembayaran</th>
                    <th class="px-6 py-3 text-center">Jenis</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Hutang</th>
                    <th class="px-6 py-3 text-center">Total</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $index => $barang)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $barangs->firstItem() + $index }}</td>
                        <td class="px-6 py-4">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4 text-center">{{ $barang->uniq_key }}</td>
                        <td class="px-6 py-4 text-center">{{ $barang->nama_toko_suplier }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($barang->harga_per_satu, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($hargaJuals[$barang->uniq_key] ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($barang->pembayaran, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">{{ ucfirst($barang->jenis_pembayaran) }}</td>
                        <td class="px-6 py-4 text-center">{{ ucfirst(str_replace('_', ' ', $barang->status_pembayaran)) }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($barang->hutang, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($barang->harga_total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <button wire:click="openEditModal({{ $barang->id }})" type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 dark:bg-yellow-400">
                                ✏️ Edit
                            </button>
                            <button wire:click="confirmDelete({{ $barang->id }})" type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 dark:bg-red-500">
                                🗑️ Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="12" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data barang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($barangs->hasPages())
        <div class="mt-6">
            {{ $barangs->links() }}
        </div>
    @endif
</div>
