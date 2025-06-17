<?php

namespace App\Livewire\Admin\Barang;

use App\Models\Hutang as HutangModel;
use App\Models\StockBarang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Daftar Hutang')]
class Hutang extends Component
{
    use WithPagination;

    // Properti untuk menampung nilai filter yang aktif
    public string $filter = 'semua';

    public function render()
    {
        // Pindahkan data kredit dari stock_barangs ke tabel hutangs jika belum ada
        $this->sinkronkanHutang();

        // Cek jika ada permintaan ekspor PDF
        if (request()->query('export') === 'pdf' && request()->query('filter') === $this->filter) {
            return $this->exportPdf();
        }

        // Ambil data hutang dengan filter yang diterapkan
        $hutangBarangs = $this->getFilteredHutangQuery()->latest()->paginate(10);

        return view('livewire.admin.barang.hutang', [
            'hutangBarangs' => $hutangBarangs
        ]);
    }

    /**
     * Mengatur nilai filter saat tombol di klik
     */
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        // Reset paginasi ke halaman 1 setiap kali filter diubah
        $this->resetPage();
    }

    /**
     * Membangun query dasar dengan filter yang diterapkan.
     * Ini digunakan baik untuk render() maupun exportPdf() untuk menghindari duplikasi kode.
     */
    private function getFilteredHutangQuery(): Builder
    {
        $query = HutangModel::query();

        switch ($this->filter) {
            case 'harian':
                // Filter data berdasarkan hari ini
                $query->whereDate('created_at', today());
                break;
            case 'mingguan':
                // Filter data dalam rentang minggu ini (Senin - Minggu)
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'bulanan':
                // Filter data berdasarkan bulan dan tahun saat ini
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'tahunan':
                // Filter data berdasarkan tahun saat ini
                $query->whereYear('created_at', now()->year);
                break;
        }

        return $query;
    }

    /**
     * Sinkronisasi data hutang dari tabel stock barang.
     * Menggunakan updateOrCreate untuk efisiensi dan mencegah duplikasi.
     */
    private function sinkronkanHutang(): void
    {
        $dataKredit = StockBarang::whereRaw('LOWER(jenis_pembayaran) = ?', ['kredit'])->get();

        foreach ($dataKredit as $data) {
            $status = strtolower($data->status_pembayaran) === 'lunas' ? 'lunas' : 'hutang';

            HutangModel::updateOrCreate(
                [
                    // Kunci unik untuk mencari data
                    'nama_barang' => $data->nama_barang,
                    'toko' => $data->nama_toko_suplier,
                ],
                [
                    // Data yang akan dibuat atau diperbarui
                    'jenis_pembayaran' => $data->jenis_pembayaran,
                    'status' => $status,
                    'nominal_hutang' => $data->hutang,
                ]
            );
        }
    }

    public function exportPdf()
    {
        // Ambil semua data (tanpa paginasi) dengan filter yang diterapkan
        $hutangBarangs = $this->getFilteredHutangQuery()->latest()->get();

        $pdf = Pdf::loadView('exports.hutang-barang', compact('hutangBarangs'))
            ->setPaper('a4', 'landscape');

        // Membuat nama file dinamis berdasarkan filter
        $namaFile = 'laporan-hutang-pembelian.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $namaFile);
    }
}
