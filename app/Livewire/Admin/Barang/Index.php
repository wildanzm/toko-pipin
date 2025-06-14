<?php

namespace App\Livewire\Admin\Barang;

use App\Models\Barang;
use App\Models\StockBarang;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
#[Title('Kelola Barang')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public bool $showEditModal = false;
    public bool $showDeleteConfirmationModal = false;

    public $editingBarang = [];

    public ?StockBarang $barangToDelete = null;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'perPage' => ['except' => 10, 'as' => 'limit'],
    ];

    public function openEditModal(StockBarang $barang)
    {
        $this->editingBarang = $barang->toArray(); // ✅ ubah ke array agar bisa @entangle di Blade
        $this->showEditModal = true;
    }

    public function exportPdf()
    {
        $barangs = StockBarang::query()
            ->when($this->search, function ($query) {
                $query->where('nama_barang', 'like', '%' . $this->search . '%')
                    ->orWhere('kodebarang', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_toko_suplier', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Hapus pluck dari tabel Barang karena tidak digunakan
        // $hargaJuals = Barang::pluck('harga_jual', 'unique_key'); ❌ hapus

        $pdf = Pdf::loadView('exports.barang', compact('barangs'))->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-barang.pdf');
    }



    public function confirmDelete(StockBarang $barang)
    {
        $this->barangToDelete = $barang;
        $this->showDeleteConfirmationModal = true;
    }

    public function deleteBarang()
    {
        if ($this->barangToDelete) {
            $this->barangToDelete->delete();
            session()->flash('message', 'Data barang berhasil dihapus.');
        }
        $this->showDeleteConfirmationModal = false;
        $this->barangToDelete = null;
    }

    public function render()
    {
        $barangs = StockBarang::query()
            ->when($this->search, function ($query) {
                $query->where('nama_barang', 'like', '%' . $this->search . '%')
                    ->orWhere('uniq_key', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_toko_suplier', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Ambil harga jual dari tabel Barang
        $hargaJuals = Barang::pluck('harga_jual', 'kodebarang');

        return view('livewire.admin.barang.index', [
            'barangs' => $barangs,
            'hargaJuals' => $hargaJuals,
        ]);
    }

    public function updateProduct()
    {
        $barang = StockBarang::find($this->editingBarang['id']);
        $barang->jenis_pembayaran = $this->editingBarang['jenis_pembayaran'];
        $barang->pembayaran = $this->editingBarang['pembayaran'] ?? null;
        $barang->save();

        session()->flash('message', 'Data berhasil diperbarui.');
        $this->showEditModal = false;
    }



    public function updateJenisPembayaran()
    {
        if ($this->editingBarang && isset($this->editingBarang['id'])) {
            $barang = StockBarang::find($this->editingBarang['id']);

            if ($barang) {
                $barang->jenis_pembayaran = $this->editingBarang['jenis_pembayaran'] ?? null;
                $barang->pembayaran = $this->editingBarang['pembayaran'] ?? null;
                if ($barang->jenis_pembayaran === 'kredit') {
                    $barang->hutang = max(($barang->harga_per_satu * $barang->kuantitas) - ($barang->pembayaran ?? 0), 0);
                    $barang->status_pembayaran = $barang->hutang > 0 ? 'belum_lunas' : 'lunas';
                } else {
                    $barang->hutang = 0;
                    $barang->status_pembayaran = 'lunas';
                }

                $barang->save();

                session()->flash('message', 'Jenis pembayaran berhasil diperbarui.');
            } else {
                session()->flash('message', 'Barang tidak ditemukan.');
            }
        }

        $this->showEditModal = false;
    }


}
