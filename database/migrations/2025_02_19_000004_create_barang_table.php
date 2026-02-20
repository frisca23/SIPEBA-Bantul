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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->foreignId('jenis_id')->constrained('jenis_barang')->onDelete('restrict');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('satuan');
            $table->integer('stok_saat_ini')->default(0);
            $table->decimal('harga_terakhir', 15, 2)->default(0);
            $table->timestamps();
            
            // Unique constraint kombinasi unit_kerja_id dan kode_barang
            $table->unique(['unit_kerja_id', 'kode_barang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
