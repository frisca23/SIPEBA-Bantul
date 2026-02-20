<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitKerja = [
            'Bagian Tata Pemerintahan (Tapem)',
            'Bagian Hukum',
            'Bagian Perekonomian, Pembangunan dan Sumber Daya Alam (SDA)',
            'Bagian Kesejahteraan Rakyat (Kesra)',
            'Bagian Umum dan Protokol',
            'Bagian Organisasi',
            'Bagian Pengadaan Barang dan Jasa',
            'Bagian Perencanaan dan Keuangan',
        ];

        foreach ($unitKerja as $nama) {
            UnitKerja::firstOrCreate(
                ['nama_unit' => $nama],
                ['nama_unit' => $nama]
            );
        }
    }
}
