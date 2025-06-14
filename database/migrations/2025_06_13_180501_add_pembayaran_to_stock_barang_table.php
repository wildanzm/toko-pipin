<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->string('pembayaran')->nullable()->after('nama_barang'); 
        });
    }

    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->dropColumn('pembayaran');
        });
    }
};
