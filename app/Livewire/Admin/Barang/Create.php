<?php

namespace App\Livewire\Admin\Barang;

use App\Models\StockBarang;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
#[Title('Tambah Barang')]
class Create extends Component
{
    public $namaToko = '';
    public $namaBarang = '';
    public $hargaBeli = 0;
    public $hargaJual = 0;
    public $stock = 0;
    public $jenisStock = '';
    public $jenisPembayaran = '';
    public $nominalPembayaran;

    protected function rules(): array
    {
        return [
            'namaToko' => ['required', 'string'],
            'namaBarang' => ['required', 'string'],
            'hargaBeli' => ['required', 'numeric', 'min:0'],
            'hargaJual' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:1'],
            'jenisStock' => ['required', 'in:liter,pcs,kg'],
            'jenisPembayaran' => ['required', 'in:tunai,kredit'],
            'nominalPembayaran' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($this->jenisPembayaran === 'kredit' && empty($value)) {
                        $fail('Nominal pembayaran wajib diisi jika memilih kredit.');
                    }
                },
            ],
        ];
    }

    protected $messages = [
        'namaToko.required' => 'Nama toko wajib diisi.',
        'namaBarang.required' => 'Nama barang wajib diisi.',
        'hargaBeli.required' => 'Harga beli wajib diisi.',
        'hargaJual.required' => 'Harga jual wajib diisi.',
        'stock.required' => 'Stok wajib diisi.',
        'jenisStock.required' => 'Jenis stock wajib dipilih.',
        'jenisPembayaran.required' => 'Jenis pembayaran wajib dipilih.',
        'jenisPembayaran.in' => 'Jenis pembayaran tidak valid.',
    ];

    private function generateKodeBarang(): string
    {
        $last = \App\Models\StockBarang::orderBy('id', 'desc')->first();
        $nextId = $last ? $last->id + 1 : 1;

        return 'TP' . str_pad($nextId, 5, '0', STR_PAD_LEFT); // hasil: TP00001, TP00002, dst.
    }


    public function saveProduct()
    {
        $validated = $this->validate();

        $hargaTotal = $this->hargaBeli * $this->stock;

        $pembayaran = $this->jenisPembayaran === 'tunai'
            ? $hargaTotal
            : ($this->nominalPembayaran ?? 0);

        $hutang = max($hargaTotal - $pembayaran, 0);
        $statusPembayaran = $hutang > 0 ? 'belum_lunas' : 'lunas';

        


        StockBarang::create([
            'nama_barang' => $this->namaBarang,
            'kodebarang' => $this->generateKodeBarang(),
            'harga_per_satu' => $this->hargaBeli,
            'harga_total' => $hargaTotal,
            'kuantitas' => $this->stock,
            'jenis_stok' => $this->jenisStock, // ✅ perbaikan di sini
            'harga_jual_per_barang' => $this->hargaJual,
            'nama_toko_suplier' => $this->namaToko,
            'pembayaran' => $pembayaran,
            'jenis_pembayaran' => $this->jenisPembayaran,
            'status_pembayaran' => $statusPembayaran,
            'hutang' => $hutang,
        ]);


        session()->flash('message', 'Barang berhasil ditambahkan ke stok.');

        // Reset form
        $this->reset([
            'namaToko', 'namaBarang', 'hargaBeli', 'hargaJual', 'stock',
            'jenisStock', 'jenisPembayaran', 'nominalPembayaran'
        ]);

        $this->stock = 0;

        return redirect()->route('admin.barang.index');
    }

    public function render()
    {
        return view('livewire.admin.barang.create');
    }
}
