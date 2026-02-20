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
        Schema::create('penerimaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->integer('jumlah_masuk');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2)->storedAs('jumlah_masuk * harga_satuan');
            $table->timestamps();
            
            // Cegah duplikasi barang dalam satu penerimaan
            $table->unique(['penerimaan_id', 'barang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_detail');
    }
};
