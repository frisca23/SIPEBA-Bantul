#!/usr/bin/env php
<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$count = DB::table('jenis_barang')->count();
echo "✓ Total Jenis Barang: " . $count . "\n";
echo str_repeat("-", 80) . "\n";

$items = DB::table('jenis_barang')->limit(12)->get();
foreach ($items as $item) {
    echo str_pad($item->kode_jenis, 15) . "| " . $item->nama_jenis . "\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "\n✓ Total Barang: " . DB::table('barang')->count() . "\n";
echo str_repeat("-", 80) . "\n";

$barang = DB::table('barang')->limit(20)->get();
foreach ($barang as $b) {
    echo str_pad($b->kode_barang, 15) . "| " . str_pad($b->nama_barang, 35) . "| " . $b->satuan . "\n";
}
?>
