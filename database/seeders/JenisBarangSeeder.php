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
            ['kode_jenis' => '170101012', 'nama_jenis' => 'Bahan Bangunan Dan Konstruksi'],
            ['kode_jenis' => '170101020', 'nama_jenis' => 'Bahan Kimia'],
            ['kode_jenis' => '170101030', 'nama_jenis' => 'Bahan Peledak'],
            ['kode_jenis' => '170101040', 'nama_jenis' => 'Bahan Bakar Dan Pelumas'],
            ['kode_jenis' => '170101050', 'nama_jenis' => 'Bahan Baku'],
            ['kode_jenis' => '170101060', 'nama_jenis' => 'Bahan Kimia Nuklir'],
            ['kode_jenis' => '170101070', 'nama_jenis' => 'Barang Dalam Proses'],
            ['kode_jenis' => '170101080', 'nama_jenis' => 'Bahan/Bibit Tanaman'],
            ['kode_jenis' => '170101090', 'nama_jenis' => 'Isi Tabung Pemadam Kebakaran'],
            ['kode_jenis' => '170101100', 'nama_jenis' => 'Isi Tabung Gas'],
            ['kode_jenis' => '170101110', 'nama_jenis' => 'Bahan/Bibit Ternak/Bibit Ikan'],
            ['kode_jenis' => '170101120', 'nama_jenis' => 'Bahan Lainnya'],
            ['kode_jenis' => '170102010', 'nama_jenis' => 'Suku Cadang Alat Angkutan'],
            ['kode_jenis' => '170102020', 'nama_jenis' => 'Suku Cadang Alat Besar'],
            ['kode_jenis' => '170102030', 'nama_jenis' => 'Suku Cadang Alat Kedokteran'],
            ['kode_jenis' => '170102040', 'nama_jenis' => 'Suku Cadang Alat Laboratorium'],
            ['kode_jenis' => '170102050', 'nama_jenis' => 'Suku Cadang Alat Pemancar'],
            ['kode_jenis' => '170102060', 'nama_jenis' => 'Suku Cadang Alat Studio Dan Komunikasi'],
            ['kode_jenis' => '170102070', 'nama_jenis' => 'Suku Cadang Alat Pertanian'],
            ['kode_jenis' => '170102080', 'nama_jenis' => 'Suku Cadang Alat Bengkel'],
            ['kode_jenis' => '170102110', 'nama_jenis' => 'Persediaan Dari Belanja Bantuan Sosial'],
            ['kode_jenis' => '170102120', 'nama_jenis' => 'Suku Cadang Lainnya'],
            ['kode_jenis' => '170103010', 'nama_jenis' => 'Alat Tulis Kantor'],
            ['kode_jenis' => '170103020', 'nama_jenis' => 'Kertas Dan Cover'],
            ['kode_jenis' => '170103030', 'nama_jenis' => 'Bahan Cetak'],
            ['kode_jenis' => '170103040', 'nama_jenis' => 'Benda Pos'],
            ['kode_jenis' => '170103050', 'nama_jenis' => 'Persediaan Dokumen/Administrasi Tender'],
            ['kode_jenis' => '170103060', 'nama_jenis' => 'Bahan Komputer'],
            ['kode_jenis' => '170103070', 'nama_jenis' => 'Perabot Kantor'],
            ['kode_jenis' => '170103080', 'nama_jenis' => 'Alat Listrik'],
            ['kode_jenis' => '170103090', 'nama_jenis' => 'Perlengkapan Dinas'],
            ['kode_jenis' => '170103100', 'nama_jenis' => 'Kaporlap Dan Perlengkapan Satwa'],
            ['kode_jenis' => '170103110', 'nama_jenis' => 'Perlengkapan Pendukung Olah Raga'],
            ['kode_jenis' => '170103120', 'nama_jenis' => 'Suvenir/Cendera Mata'],
            ['kode_jenis' => '170103130', 'nama_jenis' => 'Alat/Bahan Untuk Kegiatan Kantor Lainnya'],
            ['kode_jenis' => '170104010', 'nama_jenis' => 'Obat'],
            ['kode_jenis' => '170104020', 'nama_jenis' => 'Obat-Obatan Lainnya'],
            ['kode_jenis' => '170105010', 'nama_jenis' => 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat'],
            ['kode_jenis' => '170105020', 'nama_jenis' => 'Persediaan Untuk Dijual/Diserahkan Lainnya'],
            ['kode_jenis' => '170106010', 'nama_jenis' => 'Persediaan Untuk Tujuan Strategis/Berjaga-Jaga'],
            ['kode_jenis' => '170106020', 'nama_jenis' => 'Persediaan Untuk Tujuan Strategis/Berjaga-Jaga Lainnya'],
            ['kode_jenis' => '170107010', 'nama_jenis' => 'Natura'],
            ['kode_jenis' => '170107020', 'nama_jenis' => 'Pakan'],
            ['kode_jenis' => '170107030', 'nama_jenis' => 'Natura Dan Pakan Lainnya'],
            ['kode_jenis' => '170108010', 'nama_jenis' => 'Persediaan Penelitian Biologi'],
            ['kode_jenis' => '170108020', 'nama_jenis' => 'Persediaan Penelitian Biologi Lainnya'],
            ['kode_jenis' => '170108030', 'nama_jenis' => 'Persediaan Penelitian Teknologi'],
            ['kode_jenis' => '170108040', 'nama_jenis' => 'Persediaan Penelitian Lainnya'],
            ['kode_jenis' => '170109010', 'nama_jenis' => 'Persediaan Dalam Proses'],
            ['kode_jenis' => '170109020', 'nama_jenis' => 'Persediaan Dalam Proses Lainnya'],
            ['kode_jenis' => '170201010', 'nama_jenis' => 'Komponen Jembatan Baja'],
            ['kode_jenis' => '170201020', 'nama_jenis' => 'Komponen Jembatan Pratekan'],
            ['kode_jenis' => '170201030', 'nama_jenis' => 'Komponen Peralatan'],
            ['kode_jenis' => '170201040', 'nama_jenis' => 'Komponen Rambu-Rambu'],
            ['kode_jenis' => '170201050', 'nama_jenis' => 'Attachment'],
            ['kode_jenis' => '170201060', 'nama_jenis' => 'Komponen Lainnya'],
            ['kode_jenis' => '170202010', 'nama_jenis' => 'Pipa Air Besi Tuang (DCI)'],
            ['kode_jenis' => '170202020', 'nama_jenis' => 'Pipa Asbes Semen (ACP)'],
            ['kode_jenis' => '170202030', 'nama_jenis' => 'Pipa Baja'],
            ['kode_jenis' => '170202040', 'nama_jenis' => 'Pipa Beton Pratekan'],
            ['kode_jenis' => '170202050', 'nama_jenis' => 'Pipa Fiber Glass'],
            ['kode_jenis' => '170202060', 'nama_jenis' => 'Pipa Plastik PVC (UPVC)'],
            ['kode_jenis' => '170202070', 'nama_jenis' => 'Pipa Lainnya'],
            ['kode_jenis' => '170301010', 'nama_jenis' => 'Komponen Bekas'],
            ['kode_jenis' => '170301020', 'nama_jenis' => 'Pipa Bekas'],
            ['kode_jenis' => '170301030', 'nama_jenis' => 'Komponen Bekas Dan Pipa Bekas Lainnya'],
            ['kode_jenis' => '170101113', 'nama_jenis' => 'Bahan Makanan'],
            ['kode_jenis' => '170101114', 'nama_jenis' => 'Bahan Medis Habis Pakai'],
            ['kode_jenis' => '170101115', 'nama_jenis' => 'Isi Tabung Oksigen'],
            ['kode_jenis' => '170101116', 'nama_jenis' => 'Bahan Labu Darah'],
            ['kode_jenis' => '170102112', 'nama_jenis' => 'Suku Cadang Penerangan Jalan Umum'],
            ['kode_jenis' => '170109030', 'nama_jenis' => 'Persediaan Penambah Daya Tahan Tubuh'],
        ];

        foreach ($jenisBarang as $jenis) {
            JenisBarang::firstOrCreate(
                ['kode_jenis' => $jenis['kode_jenis']],
                $jenis
            );
        }
    }
}
