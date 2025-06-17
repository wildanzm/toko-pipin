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
            $table->string('jenis_stock', 20)->after('status_pembayaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_barangs', function (Blueprint $table) {
            $table->dropColumn('jenis_stock');
        });
    }
};
