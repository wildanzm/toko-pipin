<?php

namespace App\Livewire\Admin\Retur;

use Livewire\Component;
use App\Models\StockBarang;
use App\Models\Retur;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.admin')]
#[Title('Daftar Retur Pembelian')]
class Index extends Component
{
    use WithPagination;

    public function render()
    {
        // Sinkronisasi data retur
        $this->sinkronkanRetur();

        // Cek apakah request ingin ekspor PDF
        if (request()->query('export') === 'pdf') {
            return $this->exportPdf();
        }

        return view('livewire.admin.retur.index', [
            'returBarangs' => Retur::latest()->paginate(10),
        ]);
    }

    private function sinkronkanRetur(): void
    {
        // Ambil data dari stock_barangs yang status_pembelian = 'Dikembalikan'
        $dataRetur = StockBarang::where('status_pembelian', 'Dikembalikan')->get();

        foreach ($dataRetur as $data) {
            $sudahAda = Retur::where('kode_barang', $data->kodoebarang)
                            ->where('nama_barang', $data->nama_barang)
                            ->where('nama_toko_suplier', $data->nama_toko_suplier)
                            ->whereDate('tanggal_transaksi', $data->created_at->toDateString())
                            ->exists();

            if (! $sudahAda) {
                Retur::create([
                    'kode_barang'         => $data->kodebarang,
                    'nama_barang'         => $data->nama_barang,
                    'nama_toko_suplier'   => $data->nama_toko_suplier,
                    'kuantitas'           => $data->kuantitas,
                    'harga_per_satu'      => $data->harga_per_satu,
                    'harga_total'         => $data->harga_total,
                    'jenis_pembayaran'    => $data->jenis_pembayaran,
                    'tanggal_transaksi'   => $data->created_at->toDateString(),
                ]);
            }
        }
    }

    public function exportPdf()
    {
        $returBarangs = \App\Models\Retur::latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.retur-barang', compact('returBarangs'))
                    ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-retur-barang.pdf');
    }

}
