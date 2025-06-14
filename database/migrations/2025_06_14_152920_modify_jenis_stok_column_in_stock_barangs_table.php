<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->enum('jenis_stok', ['pcs', 'liter', 'kg'])->default('pcs');
        });
    }

    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->dropColumn('jenis_stok');
            $table->string('jenis_stok')->default('pcs');
        });
    }
};
