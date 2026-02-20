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
use Illuminate\View\View;

class LaporanController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

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
        ]);

        $tglAwal = Carbon::parse($validated['tgl_awal']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);

        // Group total nilai aset per jenis barang
        $data = DB::table('barang')
            ->join('jenis_barang', 'barang.jenis_id', '=', 'jenis_barang.id')
            ->where('barang.unit_kerja_id', $validated['unit_kerja_id'])
            ->groupBy('jenis_barang.id', 'jenis_barang.nama_jenis')
            ->selectRaw('
                jenis_barang.id,
                jenis_barang.nama_jenis,
                SUM(barang.stok_saat_ini * barang.harga_terakhir) as total_nilai_aset
            ')
            ->orderBy('jenis_barang.nama_jenis')
            ->get();

        $unitKerja = UnitKerja::find($validated['unit_kerja_id']);
        $totalNilaiAset = $data->sum('total_nilai_aset');

        return view('laporan.rekonsiliasi', compact('data', 'unitKerja', 'tglAwal', 'tglAkhir', 'totalNilaiAset'));
    }
}
