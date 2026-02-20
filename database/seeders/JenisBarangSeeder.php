<?php

namespace Database\Seeders;

use App\Models\JenisBarang;
use Illuminate\Database\Seeder;

class JenisBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisBarang = [
            ['kode_jenis' => 'ATK', 'nama_jenis' => 'Alat Tulis Kantor'],
            ['kode_jenis' => 'KIM', 'nama_jenis' => 'Bahan Kimia'],
            ['kode_jenis' => 'ELK', 'nama_jenis' => 'Elektronik'],
            ['kode_jenis' => 'FUR', 'nama_jenis' => 'Furniture'],
            ['kode_jenis' => 'JAD', 'nama_jenis' => 'Jadwal & Dokumen'],
            ['kode_jenis' => 'OTH', 'nama_jenis' => 'Lainnya'],
        ];

        foreach ($jenisBarang as $jenis) {
            JenisBarang::firstOrCreate(
                ['kode_jenis' => $jenis['kode_jenis']],
                $jenis
            );
        }
    }
}
