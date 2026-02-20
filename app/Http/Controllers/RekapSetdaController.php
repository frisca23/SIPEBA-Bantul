<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RekapSetdaController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:is-super-admin'); // Hanya super admin
    }

    /**
     * Display the SETDA recap dashboard
     * Shows aggregated asset values from all 8 units
     */
    public function index(): View
    {
        // Get all units dengan aggregated asset values
        $units = UnitKerja::with('barang')
            ->get()
            ->map(function ($unit) {
                $totalNilaiAset = $unit->barang
                    ->sum(fn($barang) => $barang->stok_saat_ini * $barang->harga_terakhir);

                return [
                    'id' => $unit->id,
                    'nama_unit' => $unit->nama_unit,
                    'total_barang' => $unit->barang->count(),
                    'total_stok' => $unit->barang->sum('stok_saat_ini'),
                    'total_nilai_aset' => $totalNilaiAset,
                ];
            })
            ->sortByDesc('total_nilai_aset')
            ->values();

        // Calculate grand total
        $grandTotalNilaiAset = $units->sum('total_nilai_aset');
        $grandTotalBarang = $units->sum('total_barang');
        $grandTotalStok = $units->sum('total_stok');

        // Get transaction stats
        $totalPenerimaan = DB::table('penerimaan')
            ->where('status', 'approved')
            ->count();

        $totalPengurangan = DB::table('pengurangan')
            ->where('status', 'approved')
            ->count();

        $totalPendingPenerimaan = DB::table('penerimaan')
            ->where('status', 'pending')
            ->count();

        $totalPendingPengurangan = DB::table('pengurangan')
            ->where('status', 'pending')
            ->count();

        return view('rekap-setda.index', compact(
            'units',
            'grandTotalNilaiAset',
            'grandTotalBarang',
            'grandTotalStok',
            'totalPenerimaan',
            'totalPengurangan',
            'totalPendingPenerimaan',
            'totalPendingPengurangan'
        ));
    }
}
