<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'unique_key',
        'harga_beli',
        'harga_jual',
        'nama_toko_suplier',
    ];
}
