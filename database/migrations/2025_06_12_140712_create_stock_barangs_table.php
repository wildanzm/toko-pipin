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
                Schema::create('stock_barangs', function (Blueprint $table) {
                    $table->id(); // ID primary key otomatis
                    $table->string('uniq_key', 7); // UniqKey barang 7 karakter
                    $table->decimal('harga_per_satu', 15, 2); // HargaBarangPerSatu
                    $table->decimal('harga_total', 15, 2);    // HargaBarangTotal
                    $table->integer('kuantitas');             // KuantitasBarang
                    $table->string('nama_toko_suplier');      // NamaTokoSuplier
                    $table->enum('jenis_pembayaran', ['kredit', 'tunai']); // Kredit / Tunai
                    $table->enum('status_pembayaran', ['belum_lunas', 'lunas']); // Status pembayaran
                    $table->decimal('hutang', 15, 2)->nullable(); // HutangJikaAda
                    $table->timestamps(); // created_at dan updated_at
                });

            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('stock_barangs');
            }
        };
