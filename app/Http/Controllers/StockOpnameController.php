<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockOpname;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StockOpnameController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = StockOpname::with(['barang', 'unitKerja', 'creator', 'verifier'])
            ->orderByDesc('tgl_opname');

        if (Auth::user()->role !== 'super_admin') {
            $query->where('unit_kerja_id', Auth::user()->unit_kerja_id);
        }

        $stockOpname = $query->paginate(15);

        return view('stock-opname.index', compact('stockOpname'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', StockOpname::class);

        $unitKerja = Auth::user()->unitKerja;
        $barang = Barang::where('unit_kerja_id', Auth::user()->unit_kerja_id)
            ->orderBy('nama_barang')
            ->get();

        return view('stock-opname.create', compact('unitKerja', 'barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', StockOpname::class);

        $validated = $request->validate([
            'tgl_opname' => 'required|date',
            'barang_id' => 'required|exists:barang,id',
            'stok_fisik_gudang' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);
        if ($barang->unit_kerja_id !== Auth::user()->unit_kerja_id) {
            return back()->withInput()->withErrors([
                'barang_id' => 'Barang bukan milik unit kerja Anda.'
            ]);
        }

        $stockOpname = StockOpname::create([
            'tgl_opname' => $validated['tgl_opname'],
            'barang_id' => $barang->id,
            'stok_di_aplikasi' => $barang->stok_saat_ini,
            'stok_fisik_gudang' => $validated['stok_fisik_gudang'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'pending',
            'unit_kerja_id' => Auth::user()->unit_kerja_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('stock-opname.show', $stockOpname)
            ->with('success', 'Stock opname berhasil dibuat, menunggu persetujuan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockOpname $stockOpname): View
    {
        $this->authorize('view', $stockOpname);

        $stockOpname->load(['barang', 'unitKerja', 'creator', 'verifier']);

        return view('stock-opname.show', compact('stockOpname'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockOpname $stockOpname): View
    {
        $this->authorize('update', $stockOpname);

        $stockOpname->load(['barang', 'unitKerja']);

        return view('stock-opname.edit', compact('stockOpname'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockOpname $stockOpname): RedirectResponse
    {
        $this->authorize('update', $stockOpname);

        $validated = $request->validate([
            'tgl_opname' => 'required|date',
            'stok_fisik_gudang' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $stockOpname->update([
            'tgl_opname' => $validated['tgl_opname'],
            'stok_fisik_gudang' => $validated['stok_fisik_gudang'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('stock-opname.show', $stockOpname)
            ->with('success', 'Stock opname berhasil diperbarui.');
    }

    /**
     * Approve the stock opname and update stock.
     */
    public function approve(Request $request, StockOpname $stockOpname): RedirectResponse
    {
        $this->authorize('approve', $stockOpname);

        if (!$stockOpname->canApprove()) {
            return back()->withErrors(['message' => 'Stock opname tidak dapat disetujui.']);
        }

        $stockOpname->update([
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $stockOpname->barang->update([
            'stok_saat_ini' => $stockOpname->stok_fisik_gudang,
        ]);

        return redirect()->route('stock-opname.show', $stockOpname)
            ->with('success', 'Stock opname berhasil disetujui dan stok diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockOpname $stockOpname): RedirectResponse
    {
        $this->authorize('delete', $stockOpname);

        $stockOpname->delete();

        return redirect()->route('stock-opname.index')
            ->with('success', 'Stock opname berhasil dihapus.');
    }
}
