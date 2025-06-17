<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            // PERBAIKAN: Ubah panjang kolom dari 10 menjadi 20
            $table->string('status_pembelian', 20)->after('status_pembayaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->dropColumn('status_pembelian');
        });
    }
};
