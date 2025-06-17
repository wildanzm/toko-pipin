<?php

namespace App\Livewire\Admin\Barang;

use App\Models\Barang;
use App\Models\StockBarang;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;

#[Layout('components.layouts.admin')]
#[Title('Data Pembelian Barang')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $filter = 'semua'; // Properti baru untuk menyimpan filter aktif

    public bool $showEditModal = false;
    public bool $showDeleteConfirmationModal = false;

    public $editingBarang = [];
    public ?StockBarang $barangToDelete = null;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'perPage' => ['except' => 10, 'as' => 'limit'],
    ];

    /**
     * Metode baru untuk mengatur filter dan mereset paginasi
     */
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    /**
     * Metode private baru untuk mendapatkan query yang sudah difilter
     * Ini digunakan untuk render() dan exportPdf() agar konsisten
     */
    private function getFilteredQuery(): Builder
    {
        $query = StockBarang::query()
            ->when($this->search, function ($query) {
                $query->where('nama_barang', 'like', '%' . $this->search . '%')
                    ->orWhere('kodebarang', 'like', '%' . $this->search . '%') // Disesuaikan agar konsisten
                    ->orWhere('nama_toko_suplier', 'like', '%' . $this->search . '%');
            });

        // Terapkan filter berdasarkan periode waktu
        switch ($this->filter) {
            case 'harian':
                $query->whereDate('created_at', today());
                break;
            case 'mingguan':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'bulanan':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'tahunan':
                $query->whereYear('created_at', now()->year);
                break;
        }

        return $query;
    }

    public function render()
    {
        // Gunakan metode getFilteredQuery() untuk mengambil data
        $barangs = $this->getFilteredQuery()
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Ambil harga jual dari tabel Barang
        $hargaJuals = Barang::pluck('harga_jual', 'kodebarang');

        return view('livewire.admin.barang.index', [
            'barangs' => $barangs,
            'hargaJuals' => $hargaJuals,
        ]);
    }

    public function exportPdf()
    {
        // Gunakan metode getFilteredQuery() untuk mengambil data PDF
        $barangs = $this->getFilteredQuery()
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('exports.barang', compact('barangs'))->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-pembelian-barang.pdf');
    }

    // --- Metode lainnya tetap sama (tidak diubah) ---

    public function openEditModal(StockBarang $barang)
    {
        $this->editingBarang = $barang->toArray();
        $this->showEditModal = true;
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
                // Ambil inputan user
                $barang->jenis_pembayaran = $this->editingBarang['jenis_pembayaran'] ?? null;
                $barang->pembayaran = $this->editingBarang['pembayaran'] ?? 0;
                $barang->status_pembelian = $this->editingBarang['status_pembelian'] ?? null;

                // Hitung harga total (dari data di DB)
                $hargaTotal = $barang->harga_per_satu * $barang->kuantitas;

                // Jika tunai, maka pembayaran = harga total
                if ($barang->jenis_pembayaran === 'tunai') {
                    $barang->pembayaran = $hargaTotal;
                }

                // Hitung hutang (harga total - pembayaran)
                $barang->hutang = max($hargaTotal - $barang->pembayaran, 0);

                // Set status_pembayaran berdasarkan status_pembelian
                if ($barang->status_pembelian === 'Dikembalikan') {
                    $barang->status_pembayaran = '-';
                } else {
                    $barang->status_pembayaran = $barang->hutang > 0 ? 'belum_lunas' : 'lunas';
                }

                // Simpan perubahan
                $barang->save();

                session()->flash('message', 'Data berhasil diperbarui.');
            } else {
                session()->flash('message', 'Barang tidak ditemukan.');
            }
        }

        $this->showEditModal = false;
    }
}
