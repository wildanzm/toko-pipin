<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->decimal('harga_jual_per_barang', 15, 2)->nullable()->after('kuantitas'); // atau after kolom yang memang ada
            $table->decimal('harga_jual_total', 15, 2)->nullable()->after('harga_jual_per_barang');
            $table->decimal('keuntungan', 15, 2)->nullable()->after('harga_jual_total');
        });
    }

    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('stock_barangs', 'harga_jual_per_barang')) {
                $table->dropColumn('harga_jual_per_barang');
            }
            if (Schema::hasColumn('stock_barangs', 'harga_jual_total')) {
                $table->dropColumn('harga_jual_total');
            }
            if (Schema::hasColumn('stock_barangs', 'keuntungan')) {
                $table->dropColumn('keuntungan');
            }
        });

    }
};

