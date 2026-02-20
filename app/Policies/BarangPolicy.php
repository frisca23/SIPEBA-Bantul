<?php

namespace App\Policies;

use App\Models\Barang;
use App\Models\User;

class BarangPolicy
{
    /**
     * Determine whether the user can view any model.
     */
    public function viewAny(User $user): bool
    {
        return true; // Read-All: Semua bisa melihat
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Barang $barang): bool
    {
        return true; // Read-All: Semua bisa melihat
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role !== 'super_admin'; // Semua non-admin bisa create
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Barang $barang): bool
    {
        // Write-Own: Hanya bisa update barang milik unit sendiri
        return $user->unit_kerja_id === $barang->unit_kerja_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Barang $barang): bool
    {
        // Write-Own: Hanya bisa delete barang milik unit sendiri
        return $user->unit_kerja_id === $barang->unit_kerja_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Barang $barang): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Barang $barang): bool
    {
        return false;
    }
}
