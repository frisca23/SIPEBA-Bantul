<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenisBarangController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jenisBarang = JenisBarang::orderBy('nama_jenis')->paginate(15);
        return view('jenis-barang.index', compact('jenisBarang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', JenisBarang::class);
        return view('jenis-barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', JenisBarang::class);

        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100|unique:jenis_barang,nama_jenis',
            'kode_jenis' => 'required|string|size:6|regex:/^[0-9]{6}$/|unique:jenis_barang,kode_jenis',
        ], [
            'kode_jenis.required' => 'Kode jenis barang wajib diisi',
            'kode_jenis.size' => 'Kode jenis barang harus 6 digit',
            'kode_jenis.regex' => 'Kode jenis barang harus berupa 6 digit angka',
        ]);

        JenisBarang::create($validated);

        return redirect()->route('jenis-barang.index')
            ->with('success', 'Jenis barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisBarang $jenisBarang): View
    {
        $jenisBarang->load('barang');
        return view('jenis-barang.show', compact('jenisBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisBarang $jenisBarang): View
    {
        $this->authorize('update', $jenisBarang);
        return view('jenis-barang.edit', compact('jenisBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisBarang $jenisBarang): RedirectResponse
    {
        $this->authorize('update', $jenisBarang);

        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100|unique:jenis_barang,nama_jenis,' . $jenisBarang->id,
            'kode_jenis' => 'required|string|size:6|regex:/^[0-9]{6}$/|unique:jenis_barang,kode_jenis,' . $jenisBarang->id,
        ], [
            'kode_jenis.required' => 'Kode jenis barang wajib diisi',
            'kode_jenis.size' => 'Kode jenis barang harus 6 digit',
            'kode_jenis.regex' => 'Kode jenis barang harus berupa 6 digit angka',
        ]);

        $jenisBarang->update($validated);

        return redirect()->route('jenis-barang.show', $jenisBarang)
            ->with('success', 'Jenis barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisBarang $jenisBarang): RedirectResponse
    {
        $this->authorize('delete', $jenisBarang);

        if ($jenisBarang->barang()->exists()) {
            return back()->withErrors([
                'message' => 'Jenis barang tidak dapat dihapus karena masih memiliki barang terkait'
            ]);
        }

        $jenisBarang->delete();

        return redirect()->route('jenis-barang.index')
            ->with('success', 'Jenis barang berhasil dihapus');
    }
}
