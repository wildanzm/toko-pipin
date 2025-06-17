<?php

namespace App\Livewire\Admin\Retur;

use App\Models\Retur;
use App\Models\StockBarang;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Daftar Retur Pembelian')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        // Sinkronisasi data retur untuk memastikan data selalu terbaru
        $this->sinkronkanRetur();

        // Cek apakah request ingin ekspor PDF
        if (request()->query('export') === 'pdf') {
            return $this->exportPdf();
        }

        return view('livewire.admin.retur.index', [
            'returBarangs' => Retur::latest()->paginate(10),
        ]);
    }

    /**
     * Sinkronkan data dari stock_barangs ke tabel returs.
     * Menggunakan updateOrCreate untuk mencegah duplikasi data.
     */
    private function sinkronkanRetur(): void
    {
        // Ambil data dari stock_barangs yang status_pembelian = 'Dikembalikan'
        $dataRetur = StockBarang::where('status_pembelian', 'Dikembalikan')->get();

        foreach ($dataRetur as $data) {
            // Gunakan updateOrCreate untuk mencari atau membuat data baru jika belum ada.
            // Ini secara efektif mencegah data duplikat.
            Retur::updateOrCreate(
                [
                    // Kriteria unik untuk mencari data retur
                    'kode_barang' => $data->kodebarang,
                    'nama_barang' => $data->nama_barang,
                    'nama_toko_suplier' => $data->nama_toko_suplier,
                    'tanggal_transaksi' => $data->created_at->toDateString(),
                ],
                [
                    // Data yang akan diisi atau diperbarui
                    'kuantitas' => $data->kuantitas,
                    'harga_per_satu' => $data->harga_per_satu,
                    'harga_total' => $data->harga_total,
                    'jenis_pembayaran' => $data->jenis_pembayaran,
                ]
            );
        }
    }

    public function exportPdf()
    {
        $returBarangs = Retur::latest()->get();

        $pdf = Pdf::loadView('exports.retur-barang', compact('returBarangs'))
            ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-retur-pembelian.pdf');
    }
}
