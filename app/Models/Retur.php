<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'nama_toko_suplier',
        'kuantitas',
        'harga_per_satu',
        'harga_total',
        'jenis_pembayaran',
        'tanggal_transaksi',
    ];
}
