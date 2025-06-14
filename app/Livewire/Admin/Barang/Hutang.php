<?php

namespace App\Livewire\Admin\Barang;

use Livewire\Component;
use App\Models\StockBarang;
use App\Models\Hutang as HutangModel;
 // pastikan modelnya tersedia
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.admin')]
#[Title('Daftar Hutang')]
class Hutang extends Component
{
    use WithPagination;

    public function render()
    {
        // Pindahkan data kredit dari stock_barangs ke tabel hutangs jika belum ada
        $this->sinkronkanHutang();

        if (request()->query('export') === 'pdf') {
            return $this->exportPdf();
        }

        return view('livewire.admin.barang.hutang', [
            'hutangBarangs' => HutangModel::latest()->paginate(10)
        ]);
    }

    private function sinkronkanHutang(): void
    {
        // Ambil semua data dari stock_barangs yang jenis_pembayaran kredit
        $dataKredit = StockBarang::whereRaw('LOWER(jenis_pembayaran) = ?', ['kredit'])->get();

        foreach ($dataKredit as $data) {
            // Cek apakah data dengan nama_barang dan toko yang sama sudah ada di tabel hutang
            $sudahAda = HutangModel::where('nama_barang', $data->nama_barang)
                              ->where('toko', $data->nama_toko_suplier)
                              ->exists();

            if (! $sudahAda) {
                $status = $data->status_pembayaran === 'lunas' ? 'lunas' : 'hutang';

                HutangModel::create([
                    'nama_barang'     => $data->nama_barang,
                    'toko'            => $data->nama_toko_suplier,
                    'jenis_pembayaran'=> $data->jenis_pembayaran,
                    'status' => $status,
                    'nominal_hutang'  => $data->hutang,
                ]);
            }
        }
    }

    public function exportPdf()
    {
        $hutangBarangs = HutangModel::latest()->get();

        $pdf = Pdf::loadView('exports.hutang-barang', compact('hutangBarangs'))
                  ->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'laporan-hutang-barang.pdf');
    }
}
