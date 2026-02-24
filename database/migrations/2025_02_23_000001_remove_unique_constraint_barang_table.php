<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key constraints temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::table('barang', function (Blueprint $table) {
            // Drop the unique constraint on unit_kerja_id and kode_barang
            $table->dropUnique('barang_unit_kerja_id_kode_barang_unique');
        });
        
        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Re-add the unique constraint if migration is rolled back
            $table->unique(['unit_kerja_id', 'kode_barang']);
        });
    }
};
