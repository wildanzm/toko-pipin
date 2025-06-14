<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->string('jenis_stock')->nullable()->after('kuantitas');
            // Jika ingin enum:
            // $table->enum('jenis_stock', ['masuk', 'keluar', 'retur'])->after('kuantitas');
        });
    }

    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->dropColumn('jenis_stock');
        });
    }
};
