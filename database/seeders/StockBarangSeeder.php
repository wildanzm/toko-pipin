<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StockBarangSeeder extends Seeder
{
    public function run(): void
    {
        // Data kuantitas untuk 4 barang (sesuai urutan barangs)
        $kuantitasList = [150, 224, 500, 100];

        // Ambil data barangs dan toko_supliers
        $barangs = DB::table('barangs')->get();
        $tokoList = DB::table('toko_supliers')->pluck('nama_toko')->toArray();

        foreach ($barangs as $index => $barang) {
            $kuantitas = $kuantitasList[$index] ?? 100;
            $harga_per_satu = $barang->harga_beli;
            $harga_total = $harga_per_satu * $kuantitas;
            $pembayaran = $harga_total;
            $hutang = $harga_total - $pembayaran;

            DB::table('stock_barangs')->insert([
                'nama_barang'        => $barang->nama_barang,
                'kodebarang'         => $barang->kodebarang,
                'harga_per_satu'     => $harga_per_satu,
                'kuantitas'          => $kuantitas,
                'harga_total'        => $harga_total,
                'pembayaran'         => $pembayaran,
                'jenis_pembayaran'   => 'tunai',
                'status_pembayaran'  => 'lunas',
                'hutang'             => $hutang,
                'nama_toko_suplier'  => $tokoList[$index] ?? 'Suplier Tidak Diketahui',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}
