<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHutangsTable extends Migration
{
    public function up(): void
    {
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id(); // ini bisa dijadikan kolom "NO" auto increment
            $table->string('nama_barang');
            $table->string('toko');
            $table->string('jenis_pembayaran'); // misal: tunai, kredit
            $table->enum('status', ['lunas', 'hutang'])->default('hutang');
            $table->decimal('nominal_hutang', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
}
