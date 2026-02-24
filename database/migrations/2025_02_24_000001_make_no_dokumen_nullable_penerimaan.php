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
        // Drop unique constraint dan buat kolom nullable
        Schema::table('penerimaan', function (Blueprint $table) {
            // Hapus unique constraint jika ada
            try {
                $table->dropUnique(['no_dokumen']);
            } catch (\Exception $e) {
                // Constraint mungkin tidak ada, lanjutkan
            }
        });

        // Modify kolom menjadi nullable dan tambah unique constraint yang baru
        Schema::table('penerimaan', function (Blueprint $table) {
            // Ubah kolom menjadi nullable dan sparse unique (hanya untuk non-null values)
            $table->string('no_dokumen')->nullable()->change();
            // Unique constraint untuk nilai yang terisi
            $table->unique('no_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan', function (Blueprint $table) {
            try {
                $table->dropUnique(['no_dokumen']);
            } catch (\Exception $e) {
                //
            }
            $table->string('no_dokumen')->change();
        });
    }
};
