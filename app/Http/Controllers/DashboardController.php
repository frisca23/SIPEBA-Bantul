<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Penerimaan;
use App\Models\PenerimaanDetail;
use App\Models\Pengurangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        $now = Carbon::now();
        $isAdmin = Auth::user()->role === 'super_admin';
        $unitKerjaId = Auth::user()->unit_kerja_id;

        // -------------------------------------------------------
        // 1. STAT CARDS
        // -------------------------------------------------------

        /**
         * Total Barang Tersedia
         * Jumlah seluruh record barang (bisa difilter per unit jika bukan super admin).
         */
        $barangQuery = Barang::query();
        if (!$isAdmin) {
            $barangQuery->where('unit_kerja_id', $unitKerjaId);
        }
        $totalBarang = $barangQuery->count();

        /**
         * Total Penerimaan (Bulan Ini)
         * Jumlah dokumen penerimaan yang dibuat pada bulan & tahun berjalan.
         */
        $penerimaanQuery = Penerimaan::whereYear('tgl_dokumen', $now->year)
            ->whereMonth('tgl_dokumen', $now->month);
        if (!$isAdmin) {
            $penerimaanQuery->where('unit_kerja_id', $unitKerjaId);
        }
        $totalPenerimaanBulanIni = $penerimaanQuery->count();

        /**
         * Total Pengurangan (Bulan Ini)
         * Jumlah dokumen pengurangan yang dibuat pada bulan & tahun berjalan.
         */
        $penguranganQuery = Pengurangan::whereYear('tgl_keluar', $now->year)
            ->whereMonth('tgl_keluar', $now->month);
        if (!$isAdmin) {
            $penguranganQuery->where('unit_kerja_id', $unitKerjaId);
        }
        $totalPenguranganBulanIni = $penguranganQuery->count();

        /**
         * Estimasi Total Aset (Rupiah)
         * Dihitung dari SUM(stok_saat_ini * harga_terakhir) pada tabel barang.
         */
        $asetQuery = Barang::selectRaw('SUM(stok_saat_ini * harga_terakhir) as total');
        if (!$isAdmin) {
            $asetQuery->where('unit_kerja_id', $unitKerjaId);
        }
        $estimasiAset = $asetQuery->value('total') ?? 0;

        // -------------------------------------------------------
        // 2. RECENT TRANSACTIONS (5 terbaru)
        // -------------------------------------------------------

        $latestPenerimaanQuery = Penerimaan::orderByDesc('tgl_dokumen')->limit(5);
        if (!$isAdmin) {
            $latestPenerimaanQuery->where('unit_kerja_id', $unitKerjaId);
        }
        $latestPenerimaan = $latestPenerimaanQuery->get();

        $latestPenguranganQuery = Pengurangan::orderByDesc('tgl_keluar')->limit(5);
        if (!$isAdmin) {
            $latestPenguranganQuery->where('unit_kerja_id', $unitKerjaId);
        }
        $latestPengurangan = $latestPenguranganQuery->get();

        // -------------------------------------------------------
        // 3. CHART DATA
        // -------------------------------------------------------

        /**
         * Chart Mutasi Barang 6 Bulan Terakhir
         * Menghitung jumlah dokumen penerimaan & pengurangan per bulan.
         */
        $chartMutasi = $this->getMutasi6Bulan($now, $isAdmin, $unitKerjaId);

        /**
         * Chart Komposisi Stok per Jenis Barang
         * Menghitung total stok_saat_ini dikelompokkan per jenis barang.
         */
        $chartJenis = $this->getStokPerJenis($isAdmin, $unitKerjaId);

        return view('dashboard', compact(
            'totalBarang',
            'totalPenerimaanBulanIni',
            'totalPenguranganBulanIni',
            'estimasiAset',
            'latestPenerimaan',
            'latestPengurangan',
            'chartMutasi',
            'chartJenis',
        ));
    }

    /**
     * Hitung jumlah penerimaan & pengurangan per bulan
     * untuk 6 bulan ke belakang dari tanggal sekarang.
     */
    private function getMutasi6Bulan(Carbon $now, bool $isAdmin, ?int $unitKerjaId): array
    {
        $labels      = [];
        $penerimaan  = [];
        $pengurangan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = $now->copy()->subMonths($i);

            $labels[] = $bulan->locale('id')->isoFormat('MMM YY');

            $penerimaanQuery = Penerimaan::whereYear('tgl_dokumen', $bulan->year)
                ->whereMonth('tgl_dokumen', $bulan->month);
            if (!$isAdmin) {
                $penerimaanQuery->where('unit_kerja_id', $unitKerjaId);
            }
            $penerimaan[] = $penerimaanQuery->count();

            $penguranganQuery = Pengurangan::whereYear('tgl_keluar', $bulan->year)
                ->whereMonth('tgl_keluar', $bulan->month);
            if (!$isAdmin) {
                $penguranganQuery->where('unit_kerja_id', $unitKerjaId);
            }
            $pengurangan[] = $penguranganQuery->count();
        }

        return compact('labels', 'penerimaan', 'pengurangan');
    }

    /**
     * Hitung total stok saat ini per jenis barang.
     */
    private function getStokPerJenis(bool $isAdmin, ?int $unitKerjaId): array
    {
        $rows = JenisBarang::with(['barang' => function ($q) use ($isAdmin, $unitKerjaId) {
                $q->select('jenis_id', 'stok_saat_ini');
                if (!$isAdmin) {
                    $q->where('unit_kerja_id', $unitKerjaId);
                }
            }])
            ->get()
            ->map(function ($jenis) {
                return [
                    'label' => $jenis->nama_jenis,
                    'total' => $jenis->barang->sum('stok_saat_ini'),
                ];
            })
            ->filter(fn ($row) => $row['total'] > 0)
            ->values();

        return [
            'labels' => $rows->pluck('label')->toArray(),
            'data'   => $rows->pluck('total')->toArray(),
        ];
    }
}
