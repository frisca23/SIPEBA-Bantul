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
        Schema::create('pengurangan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengurangan_id')->constrained('pengurangan')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->integer('jumlah_kurang');
            $table->timestamps();
            
            // Cegah duplikasi barang dalam satu pengurangan
            $table->unique(['pengurangan_id', 'barang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengurangan_detail');
    }
};
