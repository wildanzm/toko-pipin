<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum memasukkan data baru untuk menghindari duplikasi
        DB::table('barangs')->truncate();

        // Data awal yang sudah ada
        $barangs = [
            [
                'nama_barang' => 'Telor',
                'kodebarang' => 'TP00001',
                'harga_beli' => 26000,
                'harga_jual' => 28000,
                'nama_toko_suplier' => 'Adi Maja',
                'jenis_stock' => 'kg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'Minyak',
                'kodebarang' => 'TP00002',
                'harga_beli' => 16700,
                'harga_jual' => 18500,
                'nama_toko_suplier' => 'Toko Pintu 3',
                'jenis_stock' => 'liter',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'Beras',
                'kodebarang' => 'TP00003',
                'harga_beli' => 12800,
                'harga_jual' => 14000,
                'nama_toko_suplier' => 'Toko Sandi',
                'jenis_stock' => 'kg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'Gula',
                'kodebarang' => 'TP00004',
                'harga_beli' => 15000,
                'harga_jual' => 17000,
                'nama_toko_suplier' => 'Toko Sandi',
                'jenis_stock' => 'kg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Daftar data sampel untuk pembuatan data dinamis
        $sampleBarang = ['Susu UHT', 'Kopi Sachet', 'Teh Celup', 'Sabun Mandi', 'Shampoo', 'Pasta Gigi', 'Deterjen', 'Kecap Manis', 'Saus Sambal', 'Mie Instan'];
        $sampleSuplier = ['Adi Maja', 'Toko Pintu 3', 'Toko Sandi', 'Agen Jaya', 'Grosir Murah'];
        $sampleJenisStock = ['pcs', 'liter', 'kg', 'sachet', 'botol', 'dus', 'renceng'];

        $kodeCounter = 5;
        $months = [4, 5, 6]; // April, Mei, Juni

        foreach ($months as $month) {
            for ($i = 0; $i < 10; $i++) {
                $hargaBeli = rand(5000, 50000);
                $hargaJual = $hargaBeli * 1.2; // Keuntungan 20%
                $tanggal = Carbon::create(2025, $month, rand(1, 28));

                $barangs[] = [
                    'nama_barang' => $sampleBarang[array_rand($sampleBarang)],
                    'kodebarang' => 'TP' . str_pad($kodeCounter++, 5, '0', STR_PAD_LEFT),
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'nama_toko_suplier' => $sampleSuplier[array_rand($sampleSuplier)],
                    'jenis_stock' => $sampleJenisStock[array_rand($sampleJenisStock)],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ];
            }
        }

        DB::table('barangs')->insert($barangs);
    }
}
