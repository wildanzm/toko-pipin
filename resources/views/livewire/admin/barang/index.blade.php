<div>
    <h1 class="text-3xl font-bold pb-5 dark:text-white">Daftar Barang</h1>

    @if (session()->has('message'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        {{-- Input Pencarian dengan Ikon --}}
        <div class="relative w-full sm:w-1/2 md:w-1/3 lg:w-80">
            <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" id="table-search"
                class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Cari Barang...">
        </div>
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
    {{-- Input Pencarian dengan Ikon --}}
        <div class="relative w-full sm:w-1/2 md:w-1/3 lg:w-80">
            <!-- ...input pencarian... -->
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-2 w-full sm:w-auto">
            {{-- Tombol Export PDF --}}
            <button wire:click="exportPdf"
                class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700 transition">
                Export PDF
            </button>

            {{-- Tombol Tambah Produk --}}
            <a href="{{ route('admin.barang.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-700 whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Tambah Produk Baru
            </a>
        </div>
    </div>
    </div>

    {{-- Kontainer Tabel --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg border dark:border-gray-700">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 w-16">No</th>
                    <th scope="col" class="px-6 py-3">Nama Barang</th>
                    <th scope="col" class="px-6 py-3 text-center">kode barang</th>
                    <th scope="col" class="px-6 py-3 text-center">Toko  </th>
                    <th scope="col" class="px-6 py-3 text-center">Harga Beli</th>
                    <th scope="col" class="px-6 py-3 text-center">Stok</th>
                    <th scope="col" class="px-6 py-3 text-center">Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-center">Jenis Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-center">Status Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-center">Hutang</th>
                    <th scope="col" class="px-6 py-3 text-center">Total</th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $index => $barang)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $barangs->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $barang->nama_barang }}
                        </td>
                        <td>{{ $barang->kodebarang }}</td>

                        <td class="px-6 py-4 text-center">
                            {{ $barang->nama_toko_suplier }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            Rp {{ number_format($barang->harga_per_satu, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                            {{ $barang->kuantitas }} {{ $barang->jenis_stok }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            Rp {{ number_format($barang->pembayaran, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ ucfirst($barang->jenis_pembayaran) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($barang->status_pembayaran === 'lunas')
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
                            Rp {{ number_format($barang->hutang, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            Rp {{ number_format($barang->harga_total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <button wire:click="openEditModal({{ $barang->id }})" type="button"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-400 dark:hover:bg-yellow-500 dark:focus:ring-yellow-700 mr-2">
                                ✏️ Edit
                            </button>
                            <button wire:click="confirmDelete({{ $barang->id }})" type="button"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-700">
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

    {{-- Paginasi --}}
    @if ($barangs->hasPages())
        <div class="mt-6">
            {{ $barangs->links() }}
        </div>
    @endif

    {{-- Modal Edit Produk --}}
    @if ($showEditModal && $editingBarang)

        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden"
            x-data="{ showEditModal: @entangle('showEditModal') }" x-show="showEditModal" x-cloak>
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showEditModal = false">
            </div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8 w-full max-w-lg mx-auto my-8 transform transition-all"
                @click.away="showEditModal = false">
                <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Barang:
                        {{ $editingBarang['nama_barang'] ?? '-' }}</h3>
                    <button type="button" @click="showEditModal = false"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <form wire:submit.prevent="updateProduct" class="space-y-4 mt-4">
                    <div x-data="{ jenisPembayaran: @entangle('editingBarang.jenis_pembayaran') }">
                        <div class="mb-4">
                            <label for="jenis_pembayaran" class="block text-sm font-medium text-gray-700 dark:text-white">Jenis Pembayaran</label>
                            <select wire:model="editingBarang.jenis_pembayaran" id="jenis_pembayaran"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-white">
                                <option value="">-- Pilih --</option>
                                <option value="tunai">Tunai</option>
                                <option value="kredit">Kredit</option>
                            </select>
                        </div>

                        <div class="mb-4" x-show="jenisPembayaran === 'kredit'" x-transition>
                            <label for="pembayaran" class="block text-sm font-medium text-gray-700 dark:text-white">Nominal Pembayaran</label>
                            <input type="number" id="pembayaran" wire:model.defer="editingBarang.pembayaran"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-800 dark:text-white"
                                placeholder="Masukkan jumlah pembayaran">
                            @error('editingBarang.pembayaran')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    {{-- Catatan: Update gambar biasanya lebih kompleks dan mungkin memerlukan input file baru dan logika tambahan --}}
                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700 dark:hover:text-white">Batal</button>
                        <button wire:click="updateJenisPembayaran" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    @if ($showDeleteConfirmationModal && $barangToDelete)


        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden"
            x-data="{ showDeleteModal: @entangle('showDeleteConfirmationModal') }" x-show="showDeleteModal" x-cloak>
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false">
            </div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 sm:p-8 w-full max-w-md mx-auto my-8 transform transition-all"
                @click.away="showDeleteModal = false">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    <button type="button" @click="showDeleteModal = false"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Anda yakin ingin menghapus produk: <strong
                        class="font-medium">{{ $barangToDelete->nama_barang }}</strong>? Tindakan ini tidak dapat
                    diurungkan.
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="showDeleteModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-700 dark:hover:text-white">
                        Batal
                    </button>
                    <button wire:click="deleteBarang" wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif


</div>
