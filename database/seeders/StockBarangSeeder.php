<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Barang;

class StockBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel untuk data yang bersih
        DB::table('stock_barangs')->truncate();

        // Ambil data master barang yang sudah ada
        $masterBarangs = Barang::all();

        if ($masterBarangs->isEmpty()) {
            // Jika tidak ada master barang, hentikan seeder ini
            $this->command->info('Tabel "barangs" kosong, silakan jalankan BarangSeeder terlebih dahulu.');
            return;
        }

        $stockBarangs = [];
        $months = [4, 5, 6]; // April, Mei, Juni

        foreach ($months as $month) {
            // Buat 15 data acak untuk setiap bulan
            for ($i = 0; $i < 15; $i++) {
                $barang = $masterBarangs->random();
                $kuantitas = rand(10, 100);
                $pembayaran = rand(0, 1) === 1 ? 'tunai' : 'kredit';
                $hargaTotal = $barang->harga_beli * $kuantitas;
                $tanggal = Carbon::create(2025, $month, rand(1, 28), rand(8, 17));

                $bayar = 0;
                $statusPembayaran = 'belum_lunas';

                if ($pembayaran === 'tunai') {
                    $bayar = $hargaTotal;
                    $statusPembayaran = 'lunas';
                } else {
                    // Untuk kredit, bayar sebagian atau tidak sama sekali
                    $bayar = $hargaTotal * (rand(0, 5) / 10); // Bayar 0% sampai 50% dari total
                }

                $hutang = $hargaTotal - $bayar;
                if ($hutang <= 0) {
                    $statusPembayaran = 'lunas';
                }

                $stockBarangs[] = [
                    'kuantitas' => $kuantitas,
                    'nama_toko_suplier' => $barang->nama_toko_suplier,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                    'harga_per_satu' => $barang->harga_beli,
                    'harga_total' => $hargaTotal,
                    'keuntungan' => 0, // Keuntungan dihitung saat penjualan
                    'nama_barang' => $barang->nama_barang,
                    'jenis_pembayaran' => $pembayaran,
                    'pembayaran' => $bayar,
                    'hutang' => $hutang,
                    'status_pembayaran' => $statusPembayaran,
                    'jenis_stock' => $barang->jenis_stock,
                    'kodebarang' => $barang->kodebarang,
                    // PERBAIKAN: Mengatur semua status pembelian menjadi 'Diterima'
                    'status_pembelian' => 'Diterima',
                ];
            }
        }

        DB::table('stock_barangs')->insert($stockBarangs);
    }
}
