<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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
    public function index(): View
    {
<<<<<<< HEAD
<<<<<<< Updated upstream
        // Read-All: Semua user bisa lihat pengurangan dari semua unit
        $pengurangan = Pengurangan::with(['unitKerja', 'creator', 'verifier', 'detail'])
            ->orderByDesc('tgl_keluar')
            ->paginate(15);
=======
        $query = Pengurangan::with(['unitKerja', 'creator', 'verifier', 'detail.barang.jenisBarang'])
=======
        $query = Pengurangan::with(['unitKerja', 'creator', 'verifier', 'detail'])
>>>>>>> 9a8255536bda66b31d225678e21a7b5bee3ceec0
            ->orderByDesc('tgl_keluar');

        if (Auth::user()->role !== 'super_admin') {
            $query->where('unit_kerja_id', Auth::user()->unit_kerja_id);
        }

<<<<<<< HEAD
        $pengurangan = $query->paginate(50);
>>>>>>> Stashed changes
=======
        $pengurangan = $query->paginate(15);
>>>>>>> 9a8255536bda66b31d225678e21a7b5bee3ceec0

        return view('pengurangan.index', compact('pengurangan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Pengurangan::class);

        $unitKerja = Auth::user()->unitKerja;
        
        $jenisBarang = \App\Models\JenisBarang::all();
        $barang = Barang::with('jenisBarang')
            ->where('unit_kerja_id', Auth::user()->unit_kerja_id)
            ->get();

        return view('pengurangan.create', compact('unitKerja', 'jenisBarang', 'barang'));
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
            'tgl_serah' => 'nullable|date',
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
            'tgl_serah' => $validated['tgl_serah'],
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
            'tgl_serah' => 'nullable|date',
            'keperluan' => 'required|string',
            'detail' => 'required|array|min:1',
            'detail.*.barang_id' => 'required|exists:barang,id',
            'detail.*.jumlah_kurang' => 'required|integer|min:1',
        ]);

        $pengurangan->update([
            'tgl_keluar' => $validated['tgl_keluar'],
            'tgl_serah' => $validated['tgl_serah'],
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
