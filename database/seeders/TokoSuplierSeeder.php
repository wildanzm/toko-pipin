<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokoSuplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('toko_supliers')->insert([
            ['nama_toko' => 'Adi Maja'],
            ['nama_toko' => 'Toko Pintu 3'],
            ['nama_toko' => 'Toko Sandi'],
        ]);
    }
}
