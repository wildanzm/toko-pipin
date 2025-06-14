<div>
    <div>
        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Tambah Barang</h1>
                {{-- Asumsi Anda memiliki route untuk kembali ke daftar produk --}}
                <a href="{{ route('admin.barang.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    &larr; Kembali ke Daftar Barang
                </a>
            </div>

            @if (session()->has('message'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="saveProduct" class="space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                {{-- Pilih Toko --}}
                <div>
                    <label for="nama_toko" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Toko</label>
                    <input type="text" id="nama_toko" wire:model.live="namaToko"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Masukkan Nama Toko">
                    @error('namaToko') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

      
                {{-- Input Nama Barang --}}
                <div>
                    <label for="nama_barang" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Barang</label>
                    <input type="text" id="nama_barang" wire:model.live="namaBarang"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Masukkan Nama Barang">
                    @error('namaBarang') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>


                {{-- Input Harga Beli --}}
                <div>
                    <label for="harga_beli" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Beli</label>
                    <input type="number" id="harga_beli" wire:model.live="hargaBeli"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Masukkan Harga Beli">
                    @error('hargaBeli') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                {{-- Input Harga Jual --}}
                <div>
                    <label for="harga_jual" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Jual</label>
                    <input type="number" id="harga_jual" wire:model.live="hargaJual"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Masukkan Harga Jual">
                    @error('hargaJual') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Jenis Pembayaran --}}
                    <div>
                        <label for="jenis_pembayaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Pembayaran</label>
                        <select id="jenis_pembayaran" wire:model.live="jenisPembayaran"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Jenis Pembayaran --</option>
                            <option value="tunai">Tunai</option>
                            <option value="kredit">Kredit</option>
                        </select>
                        @error('jenisPembayaran') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Nominal Pembayaran (hanya jika Kredit) --}}
                    @if ($jenisPembayaran === 'kredit')
                    <div class="mt-4">
                        <label for="nominalPembayaran" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Nominal Pembayaran
                        </label>
                        <input type="number" id="nominalPembayaran" wire:model="nominalPembayaran"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                                focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Masukkan jumlah pembayaran">
                        @error('nominalPembayaran')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                        <input type="number" id="stock" wire:model.lazy="stock"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Contoh: 100">
                        @error('stock')
                            <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Jenis Stock --}}
                    <div>
                        <label for="jenis_stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Stock</label>
                        <select id="jenisStock" wire:model.defer="jenisStock"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Pilih Jenis Stock --</option>
                            <option value="liter">Liter (L)</option>
                            <option value="pcs">Pieces (pcs)</option>
                            <option value="kg">Kilogram (kg)</option>
                        </select>
                        @error('jenisStock') <span class="mt-1 text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                {{-- Tombol Aksi --}}
                <div class="flex justify-end pt-4">
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveProduct"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 disabled:opacity-75 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                        <span wire:loading.remove wire:target="saveProduct">Tambah Barang</span>
                        <span wire:loading wire:target="saveProduct">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
