<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\Pengurangan;
use App\Models\StockOpname;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LaporanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show laporan form with date filters
     */
    public function index(): View
    {
        return view('laporan.index');
    }

    /**
     * Buku Penerimaan Report
     */
    public function bukuPenerimaan(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query detail penerimaan yang approved
        $data = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->join('barang', 'penerimaan_detail.barang_id', '=', 'barang.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->select([
                'penerimaan.no_dokumen',
                'penerimaan.tgl_dokumen',
                'barang.nama_barang',
                'penerimaan_detail.jumlah_masuk',
                'penerimaan_detail.harga_satuan',
                'penerimaan_detail.total_harga',
            ])
            ->orderBy('penerimaan.tgl_dokumen')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilai = $data->sum('total_harga');

        return view('laporan.buku_penerimaan', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir', 'totalNilai'));
    }

    /**
     * Buku Pengurangan Report
     */
    public function bukuPengurangan(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query detail pengurangan yang approved
        $data = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->select([
                'pengurangan.no_bukti',
                'pengurangan.tgl_keluar',
                'pengurangan.keperluan',
                'barang.nama_barang',
                'pengurangan_detail.jumlah_kurang',
            ])
            ->orderBy('pengurangan.tgl_keluar')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        return view('laporan.buku_pengurangan', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Hasil Fisik Stock Opname Report
     */
    public function hasilFisikStockOpname(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Query stock opname yang approved
        $data = DB::table('stock_opname')
            ->join('barang', 'stock_opname.barang_id', '=', 'barang.id')
            ->where('stock_opname.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('stock_opname.status', 'approved')
            ->whereBetween('stock_opname.tgl_opname', [$tglAwal, $tglAkhir])
            ->select([
                'barang.nama_barang',
                'stock_opname.stok_di_aplikasi',
                'stock_opname.stok_fisik_gudang',
                'stock_opname.selisih',
                'barang.harga_terakhir',
                DB::raw('stock_opname.selisih * barang.harga_terakhir as total_nilai_selisih'),
            ])
            ->orderBy('barang.nama_barang')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilaiSelisih = $data->sum('total_nilai_selisih');

        return view('laporan.hasil_fisik_stock_opname', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir', 'totalNilaiSelisih'));
    }

    /**
     * Berita Acara Pemantauan Report
     * Laporan GABUNGAN (4 angka agregat) tanpa rincian barang
     */
    public function beritaAcaraPemantauan(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Saldo Awal: Total nilai aset sebelum tgl_awal
        $saldoAwal = DB::table('barang')
            ->where('unit_kerja_id', $validated['unit_kerja_id'])
            ->selectRaw('SUM(stok_saat_ini * harga_terakhir) as total')
            ->value('total') ?? 0;

        // Total Penerimaan: Total nilai penerimaan yang approved selama periode
        $totalPenerimaan = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->sum('penerimaan_detail.total_harga') ?? 0;

        // Total Pengurangan: Total nilai pengurangan yang approved selama periode
        // Untuk pengurangan, kita harus hitung nilai berdasarkan harga_satuan saat itu
        $totalPengurangan = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->selectRaw('SUM(pengurangan_detail.jumlah_kurang * barang.harga_terakhir) as total')
            ->value('total') ?? 0;

        // Saldo Akhir: Saldo Awal + Total Penerimaan - Total Pengurangan
        $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPengurangan;

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        return view('laporan.berita_acara_pemantauan', compact(
            'unitKerja',
            'tglAwal',
            'tglAkhir',
            'saldoAwal',
            'totalPenerimaan',
            'totalPengurangan',
            'saldoAkhir'
        ));
    }

    /**
     * Rekonsiliasi Persediaan Report
     * Format Pemerintah dengan struktur ASET TETAP dan ASET LANCAR
     */
    public function rekonsiliasi(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        $mapPenambahan = function (?string $sumberDana): string {
            $value = Str::lower($sumberDana ?? '');
            if (Str::contains($value, 'belanja modal')) return 'belanja_modal';
            if (Str::contains($value, 'dropping')) return 'dropping';
            if (Str::contains($value, 'hibah')) return 'hibah';
            return 'belanja_barang';
        };

        $mapPengurangan = function (?string $keperluan): string {
            $value = Str::lower($keperluan ?? '');
            if (Str::contains($value, 'penghapusan')) return 'penghapusan';
            return 'mutasi_keluar';
        };

        $initRow = function (string $label, bool $isBold = false, $jenisId = null, $namaJenis = null): array {
            return [
                'uraian' => $label,
                'is_bold' => $isBold,
                'jenis_id' => $jenisId,
                'nama_jenis' => $namaJenis,
                'saldo_awal_unit' => 0,
                'saldo_awal_val' => 0.0,
                'penambahan' => [
                    'belanja_modal' => ['unit' => 0, 'val' => 0.0],
                    'belanja_barang' => ['unit' => 0, 'val' => 0.0],
                    'dropping' => ['unit' => 0, 'val' => 0.0],
                    'hibah' => ['unit' => 0, 'val' => 0.0],
                ],
                'pengurangan' => [
                    'penghapusan' => ['unit' => 0, 'val' => 0.0],
                    'mutasi_keluar' => ['unit' => 0, 'val' => 0.0],
                ],
                'saldo_akhir_unit' => 0,
                'saldo_akhir_val' => 0.0,
                'keterangan' => '-',
            ];
        };

        // Query untuk data persediaan per barang individual
        $rows = [];

        $penerimaanBase = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->join('barang', 'penerimaan_detail.barang_id', '=', 'barang.id')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->select([
                'barang.id as barang_id',
                'barang.nama_barang',
                'jenis_barang.id as jenis_id',
                'jenis_barang.nama_jenis',
                'penerimaan_detail.jumlah_masuk',
                'penerimaan_detail.total_harga',
                'penerimaan.sumber_dana',
                'penerimaan.tgl_dokumen',
            ]);

        $penguranganBase = DB::table('pengurangan_detail')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->join('barang', 'pengurangan_detail.barang_id', '=', 'barang.id')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('pengurangan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('pengurangan.status', 'approved')
            ->select([
                'barang.id as barang_id',
                'barang.nama_barang',
                'jenis_barang.id as jenis_id',
                'jenis_barang.nama_jenis',
                'pengurangan_detail.jumlah_kurang',
                'pengurangan.keperluan',
                'pengurangan.tgl_keluar',
                'barang.harga_terakhir',
            ]);

        // Saldo Awal
        $penerimaanBefore = (clone $penerimaanBase)->where('penerimaan.tgl_dokumen', '<', $tglAwal)->get();
        foreach ($penerimaanBefore as $row) {
            $key = (string) $row->barang_id;
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($row->nama_barang, false, $row->jenis_id, $row->nama_jenis);
            }
            $rows[$key]['saldo_awal_unit'] += (int) $row->jumlah_masuk;
            $rows[$key]['saldo_awal_val'] += (float) $row->total_harga;
        }

        $penguranganBefore = (clone $penguranganBase)->where('pengurangan.tgl_keluar', '<', $tglAwal)->get();
        foreach ($penguranganBefore as $row) {
            $key = (string) $row->barang_id;
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($row->nama_barang, false, $row->jenis_id, $row->nama_jenis);
            }
            $rows[$key]['saldo_awal_unit'] -= (int) $row->jumlah_kurang;
            $rows[$key]['saldo_awal_val'] -= (int) $row->jumlah_kurang * (float) $row->harga_terakhir;
        }

        // Penambahan selama periode
        $penerimaanIn = (clone $penerimaanBase)->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])->get();
        foreach ($penerimaanIn as $row) {
            $key = (string) $row->barang_id;
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($row->nama_barang, false, $row->jenis_id, $row->nama_jenis);
            }
            $type = $mapPenambahan($row->sumber_dana);
            $rows[$key]['penambahan'][$type]['unit'] += (int) $row->jumlah_masuk;
            $rows[$key]['penambahan'][$type]['val'] += (float) $row->total_harga;
        }

        // Pengurangan selama periode
        $penguranganIn = (clone $penguranganBase)->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])->get();
        foreach ($penguranganIn as $row) {
            $key = (string) $row->barang_id;
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($row->nama_barang, false, $row->jenis_id, $row->nama_jenis);
            }
            $type = $mapPengurangan($row->keperluan);
            $value = (int) $row->jumlah_kurang * (float) $row->harga_terakhir;
            $rows[$key]['pengurangan'][$type]['unit'] += (int) $row->jumlah_kurang;
            $rows[$key]['pengurangan'][$type]['val'] += $value;
        }

        // Hitung saldo akhir
        foreach ($rows as $key => $row) {
            $penambahanVal = array_sum(array_column($row['penambahan'], 'val'));
            $penambahanUnit = array_sum(array_column($row['penambahan'], 'unit'));
            $penguranganVal = array_sum(array_column($row['pengurangan'], 'val'));
            $penguranganUnit = array_sum(array_column($row['pengurangan'], 'unit'));

            $rows[$key]['saldo_akhir_unit'] = $row['saldo_awal_unit'] + $penambahanUnit - $penguranganUnit;
            $rows[$key]['saldo_akhir_val'] = $row['saldo_awal_val'] + $penambahanVal - $penguranganVal;
        }

        // Konversi ke indexed array
        $rows = array_values($rows);

        // Struktur Laporan sesuai format pemerintah
        $groups = [];

        // ASET TETAP (placeholder dengan nilai 0 karena sistem ini hanya track persediaan)
        $asetTetapRows = [];
        
        // 1. KIBAR TANAH
        $asetTetapRows[] = array_merge($initRow('KIBAR TANAH', true), ['nomor' => 1]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Kibar Tanah'), ['nomor' => null, 'indent' => 1]);
        
        // 2. KIBAR PERALATAN DAN MESIN
        $asetTetapRows[] = array_merge($initRow('KIBAR PERALATAN DAN MESIN', true), ['nomor' => 2]);
        $asetTetapRows[] = array_merge($initRow('Peralatan dan Mesin'), ['nomor' => null, 'indent' => 1]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Kibar Peralatan dan Mesin'), ['nomor' => null, 'indent' => 1]);
        
        // 3. KIBAR GEDUNG DAN BANGUNAN
        $asetTetapRows[] = array_merge($initRow('KIBAR GEDUNG DAN BANGUNAN', true), ['nomor' => 3]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Kibar Gedung dan Bangunan'), ['nomor' => null, 'indent' => 1]);
        
        // 4. KIBAR JALAN, IRIGASI DAN JARINGAN
        $asetTetapRows[] = array_merge($initRow('KIBAR JALAN, IRIGASI DAN JARINGAN', true), ['nomor' => 4]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Kibar Jalan, Irigasi dan Jaringan'), ['nomor' => null, 'indent' => 1]);
        
        // 5. KIBAR ASET TETAP LAINNYA
        $asetTetapRows[] = array_merge($initRow('KIBAR ASET TETAP LAINNYA', true), ['nomor' => 5]);
        $asetTetapRows[] = array_merge($initRow('Buku'), ['nomor' => null, 'indent' => 1]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Kibar Aset Tetap Lainnya'), ['nomor' => null, 'indent' => 1]);
        
        // 6. KIBAR KDP
        $asetTetapRows[] = array_merge($initRow('KIBAR KDP', true), ['nomor' => 6]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Kibar KDP'), ['nomor' => null, 'indent' => 1]);
        
        // 7. ASET TIDAK BERWUJUD
        $asetTetapRows[] = array_merge($initRow('ASET TIDAK BERWUJUD', true), ['nomor' => 7]);
        $asetTetapRows[] = array_merge($initRow('Buku Kejian'), ['nomor' => null, 'indent' => 1]);
        $asetTetapRows[] = array_merge($initRow('Jumlah Aset Tidak Berwujud'), ['nomor' => null, 'indent' => 1]);

        $groups[] = [
            'label' => 'ASET TETAP',
            'rows' => $asetTetapRows,
        ];

        // ASET LANCAR - PERSEDIAAN (data aktual dari sistem)
        $persediaanRows = [];
        
        // 8. PERSEDIAAN (header kategori)
        $persediaanRows[] = array_merge($initRow('PERSEDIAAN', true), ['nomor' => 8]);
        
        // Urutkan berdasarkan nama_jenis kemudian nama_barang
        usort($rows, function($a, $b) {
            $jenisCompare = strcmp($a['nama_jenis'] ?? '', $b['nama_jenis'] ?? '');
            if ($jenisCompare !== 0) return $jenisCompare;
            return strcmp($a['uraian'], $b['uraian']);
        });
        
        $counter = 1;
        $subtotalPersediaan = $initRow('Jumlah Persediaan');
        
        foreach ($rows as $row) {
            $row['nomor'] = $counter;
            $row['indent'] = 1;
            $persediaanRows[] = $row;
            
            // Akumulasi subtotal
            $subtotalPersediaan['saldo_awal_unit'] += $row['saldo_awal_unit'];
            $subtotalPersediaan['saldo_awal_val'] += $row['saldo_awal_val'];
            foreach ($subtotalPersediaan['penambahan'] as $type => $vals) {
                $subtotalPersediaan['penambahan'][$type]['unit'] += $row['penambahan'][$type]['unit'];
                $subtotalPersediaan['penambahan'][$type]['val'] += $row['penambahan'][$type]['val'];
            }
            foreach ($subtotalPersediaan['pengurangan'] as $type => $vals) {
                $subtotalPersediaan['pengurangan'][$type]['unit'] += $row['pengurangan'][$type]['unit'];
                $subtotalPersediaan['pengurangan'][$type]['val'] += $row['pengurangan'][$type]['val'];
            }
            $subtotalPersediaan['saldo_akhir_unit'] += $row['saldo_akhir_unit'];
            $subtotalPersediaan['saldo_akhir_val'] += $row['saldo_akhir_val'];
            
            $counter++;
        }

        // Subtotal Aset Lancar
        $subtotalAsetLancar = array_merge($initRow('JUMLAH ASET LANCAR', true), ['nomor' => null, 'indent' => 0]);
        $subtotalAsetLancar['saldo_awal_unit'] = $subtotalPersediaan['saldo_awal_unit'];
        $subtotalAsetLancar['saldo_awal_val'] = $subtotalPersediaan['saldo_awal_val'];
        $subtotalAsetLancar['penambahan'] = $subtotalPersediaan['penambahan'];
        $subtotalAsetLancar['pengurangan'] = $subtotalPersediaan['pengurangan'];
        $subtotalAsetLancar['saldo_akhir_unit'] = $subtotalPersediaan['saldo_akhir_unit'];
        $subtotalAsetLancar['saldo_akhir_val'] = $subtotalPersediaan['saldo_akhir_val'];

        $persediaanRows[] = $subtotalAsetLancar;

        $groups[] = [
            'label' => 'ASET LANCAR',
            'rows' => $persediaanRows,
        ];

        // TOTAL keseluruhan (ASET TETAP + ASET LANCAR)
        $total = $initRow('JUMLAH ASET TETAP');
        $total['saldo_awal_unit'] = $subtotalPersediaan['saldo_awal_unit'];
        $total['saldo_awal_val'] = $subtotalPersediaan['saldo_awal_val'];
        $total['penambahan'] = $subtotalPersediaan['penambahan'];
        $total['pengurangan'] = $subtotalPersediaan['pengurangan'];
        $total['saldo_akhir_unit'] = $subtotalPersediaan['saldo_akhir_unit'];
        $total['saldo_akhir_val'] = $subtotalPersediaan['saldo_akhir_val'];

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        $report = [
            'groups' => $groups,
            'total' => $total,
        ];

        return view('laporan.rekonsiliasi', compact('report', 'unitKerja', 'tglAwal', 'tglAkhir'));
    }
}
