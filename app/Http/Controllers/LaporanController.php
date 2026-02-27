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
     * Per jenis barang
     */
    public function rekonsiliasi(Request $request): View
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'mode' => 'nullable|in:kategori,jenis',
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);
        $mode = $validated['mode'] ?? 'kategori';

        $mapPenambahan = function (?string $sumberDana): string {
            $value = Str::lower($sumberDana ?? '');

            if (Str::contains($value, 'belanja modal')) {
                return 'belanja_modal';
            }
            if (Str::contains($value, 'dropping')) {
                return 'dropping';
            }
            if (Str::contains($value, 'hibah')) {
                return 'hibah';
            }
            if (
                Str::contains($value, 'belanja barang')
                || Str::contains($value, 'barang/jasa')
                || Str::contains($value, 'barang jasa')
                || Str::contains($value, 'barang dan jasa')
            ) {
                return 'belanja_barang';
            }

            return 'belanja_barang';
        };

        $mapPengurangan = function (?string $keperluan): string {
            $value = Str::lower($keperluan ?? '');

            if (Str::contains($value, 'penghapusan')) {
                return 'penghapusan';
            }

            return 'mutasi_keluar';
        };

        $initRow = function (string $label, $barangId = null, $namaBarang = null): array {
            return [
                'uraian' => $label,
                'barang_id' => $barangId,
                'nama_barang' => $namaBarang,
                'is_detail' => !is_null($barangId),
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

        $getKey = function ($row) use ($mode): string {
            return $mode === 'kategori' ? 'persediaan' : (string) $row->jenis_id;
        };

        $getLabel = function ($row) use ($mode): string {
            return $mode === 'kategori' ? 'PERSEDIAAN' : $row->nama_jenis;
        };

        $rows = [];
        $detailRows = [];

        $penerimaanBase = DB::table('penerimaan_detail')
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id')
            ->join('barang', 'penerimaan_detail.barang_id', '=', 'barang.id')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('penerimaan.unit_kerja_id', $validated['unit_kerja_id'])
            ->where('penerimaan.status', 'approved')
            ->select([
                'jenis_barang.id as jenis_id',
                'jenis_barang.nama_jenis',
                'barang.id as barang_id',
                'barang.nama_barang',
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
                'jenis_barang.id as jenis_id',
                'jenis_barang.nama_jenis',
                'barang.id as barang_id',
                'barang.nama_barang',
                'pengurangan_detail.jumlah_kurang',
                'pengurangan.keperluan',
                'pengurangan.tgl_keluar',
                'barang.harga_terakhir',
            ]);

        $penerimaanBefore = (clone $penerimaanBase)
            ->where('penerimaan.tgl_dokumen', '<', $tglAwal)
            ->get();
        foreach ($penerimaanBefore as $row) {
            $key = $getKey($row);
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($getLabel($row));
            }
            $rows[$key]['saldo_awal_unit'] += (int) $row->jumlah_masuk;
            $rows[$key]['saldo_awal_val'] += (float) $row->total_harga;

            $detailKey = $key . '_' . $row->barang_id;
            if (!isset($detailRows[$detailKey])) {
                $detailRows[$detailKey] = $initRow($row->nama_barang, $row->barang_id, $row->nama_barang);
                $detailRows[$detailKey]['parent_key'] = $key;
            }
            $detailRows[$detailKey]['saldo_awal_unit'] += (int) $row->jumlah_masuk;
            $detailRows[$detailKey]['saldo_awal_val'] += (float) $row->total_harga;
        }

        $penguranganBefore = (clone $penguranganBase)
            ->where('pengurangan.tgl_keluar', '<', $tglAwal)
            ->get();
        foreach ($penguranganBefore as $row) {
            $key = $getKey($row);
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($getLabel($row));
            }
            $rows[$key]['saldo_awal_unit'] -= (int) $row->jumlah_kurang;
            $rows[$key]['saldo_awal_val'] -= (int) $row->jumlah_kurang * (float) $row->harga_terakhir;

            $detailKey = $key . '_' . $row->barang_id;
            if (!isset($detailRows[$detailKey])) {
                $detailRows[$detailKey] = $initRow($row->nama_barang, $row->barang_id, $row->nama_barang);
                $detailRows[$detailKey]['parent_key'] = $key;
            }
            $detailRows[$detailKey]['saldo_awal_unit'] -= (int) $row->jumlah_kurang;
            $detailRows[$detailKey]['saldo_awal_val'] -= (int) $row->jumlah_kurang * (float) $row->harga_terakhir;
        }

        $penerimaanIn = (clone $penerimaanBase)
            ->whereBetween('penerimaan.tgl_dokumen', [$tglAwal, $tglAkhir])
            ->get();
        foreach ($penerimaanIn as $row) {
            $key = $getKey($row);
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($getLabel($row));
            }
            $type = $mapPenambahan($row->sumber_dana);
            $rows[$key]['penambahan'][$type]['unit'] += (int) $row->jumlah_masuk;
            $rows[$key]['penambahan'][$type]['val'] += (float) $row->total_harga;

            $detailKey = $key . '_' . $row->barang_id;
            if (!isset($detailRows[$detailKey])) {
                $detailRows[$detailKey] = $initRow($row->nama_barang, $row->barang_id, $row->nama_barang);
                $detailRows[$detailKey]['parent_key'] = $key;
            }
            $detailRows[$detailKey]['penambahan'][$type]['unit'] += (int) $row->jumlah_masuk;
            $detailRows[$detailKey]['penambahan'][$type]['val'] += (float) $row->total_harga;
        }

        $penguranganIn = (clone $penguranganBase)
            ->whereBetween('pengurangan.tgl_keluar', [$tglAwal, $tglAkhir])
            ->get();
        foreach ($penguranganIn as $row) {
            $key = $getKey($row);
            if (!isset($rows[$key])) {
                $rows[$key] = $initRow($getLabel($row));
            }
            $type = $mapPengurangan($row->keperluan);
            $value = (int) $row->jumlah_kurang * (float) $row->harga_terakhir;
            $rows[$key]['pengurangan'][$type]['unit'] += (int) $row->jumlah_kurang;
            $rows[$key]['pengurangan'][$type]['val'] += $value;

            $detailKey = $key . '_' . $row->barang_id;
            if (!isset($detailRows[$detailKey])) {
                $detailRows[$detailKey] = $initRow($row->nama_barang, $row->barang_id, $row->nama_barang);
                $detailRows[$detailKey]['parent_key'] = $key;
            }
            $detailRows[$detailKey]['pengurangan'][$type]['unit'] += (int) $row->jumlah_kurang;
            $detailRows[$detailKey]['pengurangan'][$type]['val'] += $value;
        }

        foreach ($rows as $key => $row) {
            $penambahanVal = array_sum(array_column($row['penambahan'], 'val'));
            $penambahanUnit = array_sum(array_column($row['penambahan'], 'unit'));
            $penguranganVal = array_sum(array_column($row['pengurangan'], 'val'));
            $penguranganUnit = array_sum(array_column($row['pengurangan'], 'unit'));

            $rows[$key]['saldo_akhir_unit'] = $row['saldo_awal_unit'] + $penambahanUnit - $penguranganUnit;
            $rows[$key]['saldo_akhir_val'] = $row['saldo_awal_val'] + $penambahanVal - $penguranganVal;
        }

        foreach ($detailRows as $detailKey => $detail) {
            $penambahanVal = array_sum(array_column($detail['penambahan'], 'val'));
            $penambahanUnit = array_sum(array_column($detail['penambahan'], 'unit'));
            $penguranganVal = array_sum(array_column($detail['pengurangan'], 'val'));
            $penguranganUnit = array_sum(array_column($detail['pengurangan'], 'unit'));

            $detailRows[$detailKey]['saldo_akhir_unit'] = $detail['saldo_awal_unit'] + $penambahanUnit - $penguranganUnit;
            $detailRows[$detailKey]['saldo_akhir_val'] = $detail['saldo_awal_val'] + $penambahanVal - $penguranganVal;
        }

        if ($mode === 'kategori' && empty($rows)) {
            $rows['persediaan'] = $initRow('PERSEDIAAN');
        }

        $finalRows = [];
        foreach ($rows as $key => $row) {
            $finalRows[] = $row;
            
            $childDetails = array_filter($detailRows, function($detail) use ($key) {
                return isset($detail['parent_key']) && $detail['parent_key'] === $key;
            });
            
            foreach ($childDetails as $detail) {
                $finalRows[] = $detail;
            }
        }

        $total = $initRow('TOTAL');
        foreach ($rows as $row) {
            $total['saldo_awal_unit'] += $row['saldo_awal_unit'];
            $total['saldo_awal_val'] += $row['saldo_awal_val'];
            foreach ($total['penambahan'] as $type => $vals) {
                $total['penambahan'][$type]['unit'] += $row['penambahan'][$type]['unit'];
                $total['penambahan'][$type]['val'] += $row['penambahan'][$type]['val'];
            }
            foreach ($total['pengurangan'] as $type => $vals) {
                $total['pengurangan'][$type]['unit'] += $row['pengurangan'][$type]['unit'];
                $total['pengurangan'][$type]['val'] += $row['pengurangan'][$type]['val'];
            }
            $total['saldo_akhir_unit'] += $row['saldo_akhir_unit'];
            $total['saldo_akhir_val'] += $row['saldo_akhir_val'];
        }

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);

        $groups = [
            [
                'label' => 'PERSEDIAAN',
                'rows' => $finalRows,
            ],
        ];

        $assetGroups = [
            'aset_lancar' => [
                'label' => 'ASET LANCAR',
                'groups' => $groups,
            ],
            'aset_tetap' => [
                'label' => 'ASET TETAP',
                'groups' => [],
            ],
        ];

        $report = [
            'mode' => $mode,
            'asset_groups' => $assetGroups,
            'total' => $total,
        ];
        return view('laporan.rekonsiliasi', compact('report', 'unitKerja', 'tglAwal', 'tglAkhir'));
    }
}
