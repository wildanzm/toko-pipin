<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = [
            [
                'nama_barang' => 'Telor',
                'kodebarang' => 'TP00001',
                'harga_beli' => 26000,
                'harga_jual' => 28000,
                'nama_toko_suplier' => 'Adi Maja',
            ],
            [
                'nama_barang' => 'Minyak',
                'kodebarang' => 'TP00002',
                'harga_beli' => 16700,
                'harga_jual' => 18500,
                'nama_toko_suplier' => 'Toko Pintu 3',
            ],
            [
                'nama_barang' => 'Beras',
                'kodebarang' => 'TP00003',
                'harga_beli' => 12800,
                'harga_jual' => 14000,
                'nama_toko_suplier' => 'Toko Sandi',
            ],
            [
                'nama_barang' => 'GULA',
                'kodebarang' => 'TP00004',
                'harga_beli' => 15000,
                'harga_jual' => 17000,
                'nama_toko_suplier' => 'Toko Sandi',
            ],
        ];

        DB::table('barangs')->insert($barangs);
    }
}
