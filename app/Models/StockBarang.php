<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockBarang extends Model
{
    use HasFactory;

    protected $table = 'stock_barangs';

    protected $fillable = [
        'nama_barang',           // ✅ tambahkan ini
        'kodebarang',
        'jenis_stok', 
        'harga_per_satu',        // ✅ ganti dari harga_satuan (nama di tabel kamu `harga_per_satu`)
        'harga_total',
        'kuantitas',
        'nama_toko_suplier',
        'pembayaran',            // ✅ tambahkan ini
        'jenis_pembayaran',
        'status_pembayaran',
        'hutang',
    ];

}
