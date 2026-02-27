<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\UnitKerja;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Barang::with(['unitKerja', 'jenisBarang'])
            ->orderBy('nama_barang');

        $selectedUnit = $request->get('unit_kerja_id', 'all');

        if (Auth::user()->role !== 'super_admin') {
            $query->where('unit_kerja_id', Auth::user()->unit_kerja_id);
        } elseif ($selectedUnit !== 'all') {
            $query->where('unit_kerja_id', $selectedUnit);
        }

        $barang = $query->paginate(15)->withQueryString();
        $unitKerja = Auth::user()->role === 'super_admin'
            ? UnitKerja::orderBy('nama_unit')->get()
            : collect();

        if ($request->ajax()) {
            return view('barang.partials.table', compact('barang'));
        }

        return view('barang.index', compact('barang', 'unitKerja', 'selectedUnit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Barang::class);

        $unitKerja = Auth::user()->unitKerja;
        $jenisBarang = JenisBarang::all();

        return view('barang.create', compact('unitKerja', 'jenisBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Barang::class);

        $validated = $request->validate([
            'jenis_id' => 'required|exists:jenis_barang,id',
            'kode_barang' => 'required|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        Barang::create([
            'unit_kerja_id' => Auth::user()->unit_kerja_id,
            'jenis_id' => $validated['jenis_id'],
            'kode_barang' => $validated['kode_barang'],
            'nama_barang' => $validated['nama_barang'],
            'satuan' => $validated['satuan'],
            'stok_saat_ini' => 0,
            'harga_terakhir' => 0,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang): View
    {
        $this->authorize('view', $barang);

        $barang->load(['unitKerja', 'jenisBarang', 'penerimaanDetail', 'penguranganDetail']);

        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang): View
    {
        $this->authorize('update', $barang);

        $jenisBarang = JenisBarang::all();

        return view('barang.edit', compact('barang', 'jenisBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $this->authorize('update', $barang);

        $validated = $request->validate([
            'jenis_id' => 'required|exists:jenis_barang,id',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.show', $barang)
            ->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        $this->authorize('delete', $barang);

        // Cek apakah barang sudah digunakan di transaksi penerimaan
        if ($barang->penerimaanDetail()->exists()) {
            return back()->withErrors(['message' => 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi penerimaan.']);
        }

        // Cek apakah barang sudah digunakan di transaksi pengurangan
        if ($barang->penguranganDetail()->exists()) {
            return back()->withErrors(['message' => 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi pengurangan.']);
        }

        // Cek apakah barang memiliki stok
        if ($barang->stok_saat_ini > 0) {
            return back()->withErrors(['message' => 'Barang tidak dapat dihapus karena masih memiliki stok tersisa (' . $barang->stok_saat_ini . ' ' . $barang->satuan . ').']);
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang "' . $barang->nama_barang . '" berhasil dihapus');
    }
}
