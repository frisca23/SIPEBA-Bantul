<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil default unit kerja (ganti sesuai kebutuhan)
        $defaultUnitKerja = UnitKerja::first();
        if (!$defaultUnitKerja) {
            $defaultUnitKerja = UnitKerja::create([
                'kode_unit' => 'DEFAULT',
                'nama_unit' => 'Unit Kerja Default',
            ]);
        }

        // Data barang berdasarkan PDF "Jenis dan Nama Barang"
        $barangData = [
            // BAHAN KIMIA (170101020)
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102001', 'nama_barang' => 'Pembersih Kaca', 'satuan' => 'Botol'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102002', 'nama_barang' => 'Pembersih Keramik', 'satuan' => 'Botol'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102003', 'nama_barang' => 'Pembersih Lantai', 'satuan' => 'Botol'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102004', 'nama_barang' => 'Pengharum Kendaraan', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102005', 'nama_barang' => 'Pengharum Ruangan', 'satuan' => 'Botol'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102006', 'nama_barang' => 'Pengharum Ruangan Gantung', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102007', 'nama_barang' => 'Sabun Cuci Piring', 'satuan' => 'Botol'],
            ['kode_jenis' => '170101020', 'kode_barang' => '17010102008', 'nama_barang' => 'Sabun Mandi', 'satuan' => 'Pcs'],

            // BAHAN BAKAR DAN PELUMAS (170101040)
            ['kode_jenis' => '170101040', 'kode_barang' => '17010104001', 'nama_barang' => 'BBM', 'satuan' => 'Liter'],

            // ALAT TULIS KANTOR (170103010)
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301001', 'nama_barang' => 'Bolpoint Standar', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301002', 'nama_barang' => 'Ballpoint Boxi', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301003', 'nama_barang' => 'Binder Klip 105 Kecil', 'satuan' => 'Box'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301004', 'nama_barang' => 'Binder Klip 111', 'satuan' => 'Box'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301005', 'nama_barang' => 'Binder Klip 260', 'satuan' => 'Box'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301006', 'nama_barang' => 'Flashdisk', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301007', 'nama_barang' => 'Stapler', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301008', 'nama_barang' => 'Isi Stapler', 'satuan' => 'Box'],
            ['kode_jenis' => '170103010', 'kode_barang' => '17010301009', 'nama_barang' => 'Paper Clip', 'satuan' => 'Box'],

            // KERTAS DAN COVER (170103020)
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302001', 'nama_barang' => 'HVS F4', 'satuan' => 'Rim'],
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302002', 'nama_barang' => 'Stiknot Besar', 'satuan' => 'Pad'],
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302003', 'nama_barang' => 'Stiknot Kecil', 'satuan' => 'Pad'],
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302004', 'nama_barang' => 'Stopmap', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302005', 'nama_barang' => 'Kertas HVS F4 70 Gr', 'satuan' => 'Rim'],
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302006', 'nama_barang' => 'Kertas Cover', 'satuan' => 'Rim'],
            ['kode_jenis' => '170103020', 'kode_barang' => '17010302007', 'nama_barang' => 'Stopmap Snelhekter', 'satuan' => 'Pcs'],

            // BENDA POS (170103040)
            ['kode_jenis' => '170103040', 'kode_barang' => '17010304001', 'nama_barang' => 'Meterai', 'satuan' => 'Pcs'],

            // BAHAN KOMPUTER (170103060)
            ['kode_jenis' => '170103060', 'kode_barang' => '17010306001', 'nama_barang' => 'Keyboard', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103060', 'kode_barang' => '17010306002', 'nama_barang' => 'Mouse Wireless', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103060', 'kode_barang' => '17010306003', 'nama_barang' => 'Refil Toner', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103060', 'kode_barang' => '17010306004', 'nama_barang' => 'Toner Cartridge', 'satuan' => 'Pcs'],

            // PERABOT KANTOR (170103070)
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307001', 'nama_barang' => 'Sapu Lantai', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307002', 'nama_barang' => 'Tissu Kering', 'satuan' => 'Box'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307003', 'nama_barang' => 'Tissu Basah', 'satuan' => 'Ball'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307004', 'nama_barang' => 'Tissu Gulung', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307005', 'nama_barang' => 'Refill Kain Pel', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307006', 'nama_barang' => 'Tempat Tissu', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307007', 'nama_barang' => 'Kanebo', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103070', 'kode_barang' => '17010307008', 'nama_barang' => 'Tisu Wajah', 'satuan' => 'Box'],

            // ALAT LISTRIK (170103080)
            ['kode_jenis' => '170103080', 'kode_barang' => '17010308001', 'nama_barang' => 'Alkaline A2', 'satuan' => 'Pcs'],
            ['kode_jenis' => '170103080', 'kode_barang' => '17010308002', 'nama_barang' => 'Alkaline A3', 'satuan' => 'Pcs'],

            // ALAT/BAHAN UNTUK KEGIATAN KANTOR LAINNYA (170103130)
            ['kode_jenis' => '170103130', 'kode_barang' => '17010313001', 'nama_barang' => 'Handuk Wastafel', 'satuan' => 'Pcs'],
        ];

        // Insert barang dengan unit kerja default
        foreach ($barangData as $barang) {
            $jenisBarang = JenisBarang::where('kode_jenis', $barang['kode_jenis'])->first();
            
            if ($jenisBarang) {
                Barang::firstOrCreate(
                    [
                        'unit_kerja_id' => $defaultUnitKerja->id,
                        'kode_barang' => $barang['kode_barang'],
                    ],
                    [
                        'unit_kerja_id' => $defaultUnitKerja->id,
                        'jenis_id' => $jenisBarang->id,
                        'kode_barang' => $barang['kode_barang'],
                        'nama_barang' => $barang['nama_barang'],
                        'satuan' => $barang['satuan'],
                        'stok_saat_ini' => 0,
                        'harga_terakhir' => 0,
                    ]
                );
            }
        }
    }
}
