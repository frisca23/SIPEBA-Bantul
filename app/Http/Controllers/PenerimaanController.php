<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\PenerimaanDetail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $query = Penerimaan::with(['unitKerja', 'creator', 'verifier', 'detail'])
            ->orderByDesc('tgl_dokumen');

        if (Auth::user()->role !== 'super_admin') {
            $query->where('unit_kerja_id', Auth::user()->unit_kerja_id);
        }

        $penerimaan = $query->paginate(15);

        return view('penerimaan.index', compact('penerimaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Penerimaan::class);

        $unitKerja = Auth::user()->unitKerja;
        $barang = Barang::where('unit_kerja_id', Auth::user()->unit_kerja_id)
            ->get();

        return view('penerimaan.create', compact('unitKerja', 'barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Penerimaan::class);

        $validated = $request->validate([
            'no_dokumen' => 'nullable|string',
            'tgl_dokumen' => 'required|date',
            'sumber_dana' => 'required|string',
            'tahun_anggaran' => 'required|integer|digits:4',
            'keterangan' => 'nullable|string',
            'detail' => 'required|array|min:1',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.jumlah_masuk' => 'required|integer|min:1',
            'detail.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        // Validasi duplikat barang_id dalam satu penerimaan
        $barangIds = array_column($validated['detail'], 'barang_id');
        $duplicateBarang = array_diff_assoc($barangIds, array_unique($barangIds));
        
        if (!empty($duplicateBarang)) {
            return back()->withInput()->withErrors([
                'detail' => 'Ada barang yang ditambahkan lebih dari satu kali. Setiap barang hanya boleh ditambahkan sekali per penerimaan.'
            ]);
        }

        // Validasi bahwa semua barang milik unit kerja yang sama
        $invalidBarang = Barang::whereIn('id', $barangIds)
            ->where('unit_kerja_id', '!=', Auth::user()->unit_kerja_id)
            ->exists();

        if ($invalidBarang) {
            return back()->withInput()->withErrors([
                'detail' => 'Beberapa barang bukan milik unit kerja Anda'
            ]);
        }

        // Create penerimaan
        $penerimaan = Penerimaan::create([
            'unit_kerja_id' => Auth::user()->unit_kerja_id,
            'no_dokumen' => $validated['no_dokumen'],
            'tgl_dokumen' => $validated['tgl_dokumen'],
            'sumber_dana' => $validated['sumber_dana'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'pending', // Default status
            'created_by' => Auth::id(),
        ]);

        // Create penerimaan details
        foreach ($validated['detail'] as $detail) {
            PenerimaanDetail::create([
                'penerimaan_id' => $penerimaan->id,
                'barang_id' => $detail['barang_id'],
                'jumlah_masuk' => $detail['jumlah_masuk'],
                'harga_satuan' => $detail['harga_satuan'],
            ]);
        }

        return redirect()->route('penerimaan.show', $penerimaan)
            ->with('success', 'Penerimaan berhasil dibuat, menunggu persetujuan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penerimaan $penerimaan): View
    {
        $this->authorize('view', $penerimaan);

        $penerimaan->load(['unitKerja', 'creator', 'verifier', 'detail.barang']);

        return view('penerimaan.show', compact('penerimaan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penerimaan $penerimaan): View
    {
        $this->authorize('update', $penerimaan);

        $barang = Barang::where('unit_kerja_id', Auth::user()->unit_kerja_id)->get();
        $penerimaan->load('detail');

        return view('penerimaan.edit', compact('penerimaan', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penerimaan $penerimaan): RedirectResponse
    {
        $this->authorize('update', $penerimaan);

        $validated = $request->validate([
            'tgl_dokumen' => 'required|date',
            'sumber_dana' => 'required|string',
            'tahun_anggaran' => 'required|integer|digits:4',
            'keterangan' => 'nullable|string',
            'detail' => 'required|array|min:1',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.jumlah_masuk' => 'required|integer|min:1',
            'detail.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        // Validasi duplikat barang_id dalam satu penerimaan
        $barangIds = array_column($validated['detail'], 'barang_id');
        $duplicateBarang = array_diff_assoc($barangIds, array_unique($barangIds));
        
        if (!empty($duplicateBarang)) {
            return back()->withInput()->withErrors([
                'detail' => 'Ada barang yang ditambahkan lebih dari satu kali. Setiap barang hanya boleh ditambahkan sekali per penerimaan.'
            ]);
        }

        $penerimaan->update([
            'tgl_dokumen' => $validated['tgl_dokumen'],
            'sumber_dana' => $validated['sumber_dana'],
            'tahun_anggaran' => $validated['tahun_anggaran'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // Delete existing details dan recreate
        $penerimaan->detail()->delete();
        foreach ($validated['detail'] as $detail) {
            PenerimaanDetail::create([
                'penerimaan_id' => $penerimaan->id,
                'barang_id' => $detail['barang_id'],
                'jumlah_masuk' => $detail['jumlah_masuk'],
                'harga_satuan' => $detail['harga_satuan'],
            ]);
        }

        return redirect()->route('penerimaan.show', $penerimaan)
            ->with('success', 'Penerimaan berhasil diperbarui');
    }

    /**
     * Approve the penerimaan and update stock.
     */
    public function approve(Request $request, Penerimaan $penerimaan): RedirectResponse
    {
        $this->authorize('approve', $penerimaan);

        if (!$penerimaan->canApprove()) {
            return back()->withErrors(['message' => 'Penerimaan tidak dapat disetujui']);
        }

        // Update status
        $penerimaan->update([
            'status' => 'approved',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update stok dan harga di tabel barang
        foreach ($penerimaan->detail as $detail) {
            $barang = $detail->barang;
            $barang->increment('stok_saat_ini', $detail->jumlah_masuk);
            $barang->update([
                'harga_terakhir' => $detail->harga_satuan,
            ]);
        }

        return redirect()->route('penerimaan.show', $penerimaan)
            ->with('success', 'Penerimaan berhasil disetujui dan stok diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penerimaan $penerimaan): RedirectResponse
    {
        $this->authorize('delete', $penerimaan);

        $penerimaan->delete();

        return redirect()->route('penerimaan.index')
            ->with('success', 'Penerimaan berhasil dihapus');
    }
}
