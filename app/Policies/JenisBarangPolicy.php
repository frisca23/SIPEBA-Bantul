<?php

namespace App\Policies;

use App\Models\JenisBarang;
use App\Models\User;

class JenisBarangPolicy
{
    /**
     * Determine whether the user can view any model.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua bisa melihat data jenis barang
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JenisBarang $jenisBarang): bool
    {
        return true; // Semua bisa melihat data jenis barang
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Semua kepala bagian dan pengurus barang bisa menambah jenis barang
        return in_array($user->role, ['kepala_bagian', 'pengurus_barang']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JenisBarang $jenisBarang): bool
    {
        // Semua kepala bagian dan pengurus barang bisa mengubah jenis barang
        return in_array($user->role, ['kepala_bagian', 'pengurus_barang']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JenisBarang $jenisBarang): bool
    {
        // Semua kepala bagian dan pengurus barang bisa menghapus jenis barang
        return in_array($user->role, ['kepala_bagian', 'pengurus_barang']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JenisBarang $jenisBarang): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JenisBarang $jenisBarang): bool
    {
        return false;
    }
}
