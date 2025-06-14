<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    protected $table = 'hutangs';

    protected $fillable = [
        'nama_barang',
        'toko',
        'jenis_pembayaran',
        'status',
        'nominal_hutang',
    ];
}
