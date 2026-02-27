<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PenerimaanDetail;
use App\Models\Pengurangan;
use App\Models\PenguranganDetail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PenguranganController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $selectedBarang = $request->get('barang_id', 'all');
        $selectedTahun = $request->get('tahun', 'all');

        $query = PenguranganDetail::select('pengurangan_detail.*')
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
            ->with(['barang', 'pengurangan.unitKerja', 'pengurangan.creator'])
            ->orderByDesc('pengurangan.tgl_keluar')
            ->orderByDesc('pengurangan_detail.id');

        if (Auth::user()->role !== 'super_admin') {
            $query->where('pengurangan.unit_kerja_id', Auth::user()->unit_kerja_id);
        }

        if ($selectedBarang !== 'all') {
            $query->where('pengurangan_detail.barang_id', $selectedBarang);
        }

        if ($selectedTahun !== 'all') {
            $query->whereYear('pengurangan.tgl_keluar', $selectedTahun);
        }

        $penguranganDetails = $query->paginate(15)->withQueryString();

        $barangOptions = Barang::query()
            ->when(Auth::user()->role !== 'super_admin', function ($barangQuery) {
                $barangQuery->where('unit_kerja_id', Auth::user()->unit_kerja_id);
            })
            ->orderBy('nama_barang')
            ->get(['id', 'nama_barang']);

        $tahunOptionsQuery = Pengurangan::query()
            ->selectRaw('YEAR(tgl_keluar) as tahun')
            ->distinct()
            ->orderByDesc('tahun');

        if (Auth::user()->role !== 'super_admin') {
            $tahunOptionsQuery->where('unit_kerja_id', Auth::user()->unit_kerja_id);
        }

        if ($selectedBarang !== 'all') {
            $tahunOptionsQuery->join('pengurangan_detail', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id')
                ->where('pengurangan_detail.barang_id', $selectedBarang);
        }

        $tahunOptions = $tahunOptionsQuery->pluck('tahun');

        $totalKeluarQuery = PenguranganDetail::query()
            ->join('pengurangan', 'pengurangan_detail.pengurangan_id', '=', 'pengurangan.id');

        if (Auth::user()->role !== 'super_admin') {
            $totalKeluarQuery->where('pengurangan.unit_kerja_id', Auth::user()->unit_kerja_id);
        }

        if ($selectedBarang !== 'all') {
            $totalKeluarQuery->where('pengurangan_detail.barang_id', $selectedBarang);
        }

        if ($selectedTahun !== 'all') {
            $totalKeluarQuery->whereYear('pengurangan.tgl_keluar', $selectedTahun);
        }

        $totalKeluar = (int) $totalKeluarQuery->sum('pengurangan_detail.jumlah_kurang');

        $totalMasukQuery = PenerimaanDetail::query()
            ->join('penerimaan', 'penerimaan_detail.penerimaan_id', '=', 'penerimaan.id');

        if (Auth::user()->role !== 'super_admin') {
            $totalMasukQuery->where('penerimaan.unit_kerja_id', Auth::user()->unit_kerja_id);
        }

        if ($selectedBarang !== 'all') {
            $totalMasukQuery->where('penerimaan_detail.barang_id', $selectedBarang);
        }

        if ($selectedTahun !== 'all') {
            $totalMasukQuery->whereYear('penerimaan.tgl_dokumen', $selectedTahun);
        }

        $totalMasuk = (int) $totalMasukQuery->sum('penerimaan_detail.jumlah_masuk');
        $saldo = $totalMasuk - $totalKeluar;

        return view('pengurangan.index', compact(
            'penguranganDetails',
            'barangOptions',
            'tahunOptions',
            'selectedBarang',
            'selectedTahun',
            'totalMasuk',
            'totalKeluar',
            'saldo'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Pengurangan::class);

        $unitKerja = Auth::user()->unitKerja;
        $barang = Barang::where('unit_kerja_id', Auth::user()->unit_kerja_id)
            ->get();

        return view('pengurangan.create', compact('unitKerja', 'barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Pengurangan::class);

        $validated = $request->validate([
            'no_bukti' => 'required|string|unique:pengurangan,no_bukti',
            'tgl_keluar' => 'required|date',
            'keperluan' => 'required|string',
            'detail' => 'required|array|min:1',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.jumlah_kurang' => 'required|integer|min:1',
        ]);

        // Validasi bahwa semua barang milik unit kerja yang sama
        $barangIds = array_column($validated['detail'], 'barang_id');
        $invalidBarang = Barang::whereIn('id', $barangIds)
            ->where('unit_kerja_id', '!=', Auth::user()->unit_kerja_id)
            ->exists();

        if ($invalidBarang) {
            return back()->withInput()->withErrors([
                'detail' => 'Beberapa barang bukan milik unit kerja Anda'
            ]);
        }

        // Create pengurangan
        $pengurangan = Pengurangan::create([
            'unit_kerja_id' => Auth::user()->unit_kerja_id,
            'no_bukti' => $validated['no_bukti'],
            'tgl_keluar' => $validated['tgl_keluar'],
            'keperluan' => $validated['keperluan'],
            'status' => 'pending', // Default status
            'created_by' => Auth::id(),
        ]);

        // Create pengurangan details
        foreach ($validated['detail'] as $detail) {
            PenguranganDetail::create([
                'pengurangan_id' => $pengurangan->id,
                'barang_id' => $detail['barang_id'],
                'jumlah_kurang' => $detail['jumlah_kurang'],
            ]);
        }

        return redirect()->route('pengurangan.show', $pengurangan)
            ->with('success', 'Pengurangan berhasil dibuat, menunggu persetujuan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengurangan $pengurangan): View
    {
        $this->authorize('view', $pengurangan);

        $pengurangan->load(['unitKerja', 'creator', 'verifier', 'detail.barang']);

        return view('pengurangan.show', compact('pengurangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengurangan $pengurangan): View
    {
        $this->authorize('update', $pengurangan);

        $barang = Barang::where('unit_kerja_id', Auth::user()->unit_kerja_id)->get();
        $pengurangan->load('detail');

        return view('pengurangan.edit', compact('pengurangan', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengurangan $pengurangan): RedirectResponse
    {
        $this->authorize('update', $pengurangan);

        $validated = $request->validate([
            'tgl_keluar' => 'required|date',
            'keperluan' => 'required|string',
            'detail' => 'required|array|min:1',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.jumlah_kurang' => 'required|integer|min:1',
        ]);

        $pengurangan->update([
            'tgl_keluar' => $validated['tgl_keluar'],
            'keperluan' => $validated['keperluan'],
        ]);

        // Delete existing details dan recreate
        $pengurangan->detail()->delete();
        foreach ($validated['detail'] as $detail) {
            PenguranganDetail::create([
                'pengurangan_id' => $pengurangan->id,
                'barang_id' => $detail['barang_id'],
                'jumlah_kurang' => $detail['jumlah_kurang'],
            ]);
        }

        return redirect()->route('pengurangan.show', $pengurangan)
            ->with('success', 'Pengurangan berhasil diperbarui');
    }

    /**
     * Approve the pengurangan and update stock.
     */
    public function approve(Request $request, Pengurangan $pengurangan): RedirectResponse
    {
        $this->authorize('approve', $pengurangan);

        if (!$pengurangan->canApprove()) {
            return back()->withErrors(['message' => 'Pengurangan tidak dapat disetujui']);
        }

        // Validasi stok sebelum approve
        foreach ($pengurangan->detail as $detail) {
            $barang = $detail->barang;
            if ($barang->stok_saat_ini < $detail->jumlah_kurang) {
                return back()->withErrors([
                    'message' => "Stok tidak cukup untuk '{$barang->nama_barang}'. "
                        . "Stok tersedia: {$barang->stok_saat_ini}, diminta: {$detail->jumlah_kurang}"
                ]);
            }
        }

        // Update status
        $pengurangan->update([
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update stok di tabel barang
        foreach ($pengurangan->detail as $detail) {
            $detail->barang->decrement('stok_saat_ini', $detail->jumlah_kurang);
        }

        return redirect()->route('pengurangan.show', $pengurangan)
            ->with('success', 'Pengurangan berhasil disetujui dan stok diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengurangan $pengurangan): RedirectResponse
    {
        $this->authorize('delete', $pengurangan);

        $pengurangan->delete();

        return redirect()->route('pengurangan.index')
            ->with('success', 'Pengurangan berhasil dihapus');
    }
}
