<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->enum('status_pembelian',['Diterima', 'Dikembalikan'] )->default('Diterima')->after('status_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->dropColumn('status_pembelian');
        });
    }
};
