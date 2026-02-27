$count = DB::table('jenis_barang')->count();
echo "Total Jenis Barang: " . $count . "\n";
$items = DB::table('jenis_barang')->limit(12)->get();
foreach ($items as $item) {
    echo str_pad($item->kode_jenis, 15) . "| " . $item->nama_jenis . "\n";
}
echo "\nTotal Barang: " . DB::table('barang')->count() . "\n\n";
$barang = DB::table('barang')->limit(15)->get();
foreach ($barang as $b) {
    echo str_pad($b->kode_barang, 15) . "| " . str_pad($b->nama_barang, 35) . "| " . $b->satuan . "\n";
}
