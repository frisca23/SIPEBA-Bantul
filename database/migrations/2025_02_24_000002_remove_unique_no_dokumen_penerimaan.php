<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drop UNIQUE constraint dari no_dokumen agar bisa input nomor yang sama
     * per unit kerja atau bahkan dalam unit kerja yang sama (untuk catatan double data)
     */
    public function up(): void
    {
        try {
            // Drop unique constraint using raw SQL
            DB::statement('ALTER TABLE penerimaan DROP INDEX penerimaan_no_dokumen_unique');
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
            // Re-apply unique constraint
            DB::statement('ALTER TABLE penerimaan ADD UNIQUE KEY penerimaan_no_dokumen_unique (no_dokumen)');
        } catch (\Exception $e) {
            //
        }
    }
};


