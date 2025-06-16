<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');
            $table->string('nama_toko_suplier');
            $table->integer('kuantitas');
            $table->decimal('harga_per_satu', 15, 2);
            $table->decimal('harga_total', 15, 2);
            $table->string('jenis_pembayaran')->nullable();
            $table->date('tanggal_transaksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returs');
    }
};
