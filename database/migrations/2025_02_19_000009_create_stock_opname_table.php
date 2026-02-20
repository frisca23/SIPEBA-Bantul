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
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_opname');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->integer('stok_di_aplikasi');
            $table->integer('stok_fisik_gudang');
            $table->integer('selisih')->storedAs('stok_fisik_gudang - stok_di_aplikasi');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['unit_kerja_id', 'tgl_opname']);
            $table->index(['barang_id', 'tgl_opname']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname');
    }
};
