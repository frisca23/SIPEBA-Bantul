<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\JenisBarang;
use App\Models\Barang;

echo "=== DATA JENIS BARANG ===\n";
echo str_pad("KODE", 15) . str_pad("NAMA JENIS", 50) . "\n";
echo str_repeat("-", 65) . "\n";
$jenisBarang = JenisBarang::all();
foreach ($jenisBarang as $jenis) {
    echo str_pad($jenis->kode_jenis, 15) . str_pad($jenis->nama_jenis, 50) . "\n";
}
echo "\nTotal Jenis Barang: " . JenisBarang::count() . "\n\n";

echo "=== DATA BARANG (Sample) ===\n";
echo str_pad("KODE BARANG", 15) . str_pad("NAMA BARANG", 40) . str_pad("SATUAN", 10) . "\n";
echo str_repeat("-", 65) . "\n";
$barang = Barang::limit(20)->get();
foreach ($barang as $b) {
    echo str_pad($b->kode_barang, 15) . str_pad($b->nama_barang, 40) . str_pad($b->satuan, 10) . "\n";
}
echo "\nTotal Barang: " . Barang::count() . "\n";
?>
