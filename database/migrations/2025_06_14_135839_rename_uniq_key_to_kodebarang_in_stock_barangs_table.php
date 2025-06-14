<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->renameColumn('uniq_key', 'kodebarang');
        });
    }

    public function down(): void {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->renameColumn('kodebarang', 'uniq_key');
        });
    }
};
