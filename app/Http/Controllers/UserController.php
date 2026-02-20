<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Guard: only super_admin can access this controller.
     */
    private function authorizeAdmin(): void
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengelola user.');
        }
    }

    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $this->authorizeAdmin();

        $users = User::with('unitKerja')
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show form to create a new user.
     */
    public function create(): View
    {
        $this->authorizeAdmin();

        $unitKerja = UnitKerja::orderBy('nama_unit')->get();
        $roles = [
            'super_admin'     => 'Super Admin',
            'kepala_bagian'   => 'Kepala Bagian',
            'pengurus_barang' => 'Pengurus Barang',
        ];

        return view('users.create', compact('unitKerja', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:100|unique:users,username',
            'password'     => 'required|string|min:8|confirmed',
            'role'         => 'required|in:super_admin,kepala_bagian,pengurus_barang',
            'unit_kerja_id'=> 'nullable|exists:unit_kerja,id',
        ]);

        User::create([
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'password'      => Hash::make($validated['password']),
            'role'          => $validated['role'],
            'unit_kerja_id' => $validated['unit_kerja_id'] ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show form to edit an existing user.
     */
    public function edit(User $user): View
    {
        $this->authorizeAdmin();

        $unitKerja = UnitKerja::orderBy('nama_unit')->get();
        $roles = [
            'super_admin'     => 'Super Admin',
            'kepala_bagian'   => 'Kepala Bagian',
            'pengurus_barang' => 'Pengurus Barang',
        ];

        return view('users.edit', compact('user', 'unitKerja', 'roles'));
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($user->id)],
            'password'     => 'nullable|string|min:8|confirmed',
            'role'         => 'required|in:super_admin,kepala_bagian,pengurus_barang',
            'unit_kerja_id'=> 'nullable|exists:unit_kerja,id',
        ]);

        $data = [
            'name'          => $validated['name'],
            'username'      => $validated['username'],
            'role'          => $validated['role'],
            'unit_kerja_id' => $validated['unit_kerja_id'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
