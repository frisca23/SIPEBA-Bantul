<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drop COMPOSITE UNIQUE constraint dari (unit_kerja_id, no_dokumen)
     * agar bisa input nomor faktur yang sama di unit kerja yang sama
     */
    public function up(): void
    {
        try {
            // Drop composite unique constraint
            DB::statement('ALTER TABLE penerimaan DROP INDEX penerimaan_unit_kerja_id_no_dokumen_unique');
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            // Re-apply composite unique constraint
            DB::statement('ALTER TABLE penerimaan ADD UNIQUE KEY penerimaan_unit_kerja_id_no_dokumen_unique (unit_kerja_id, no_dokumen)');
        } catch (\Exception $e) {
            //
        }
    }
};
