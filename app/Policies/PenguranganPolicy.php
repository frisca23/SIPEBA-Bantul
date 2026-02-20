<?php

namespace App\Policies;

use App\Models\Pengurangan;
use App\Models\User;

class PenguranganPolicy
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
    public function view(User $user, Pengurangan $pengurangan): bool
    {
        return true; // Read-All: Semua bisa melihat
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role !== 'super_admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pengurangan $pengurangan): bool
    {
        // Write-Own: Hanya bisa update pengurangan milik unit sendiri
        return $user->unit_kerja_id === $pengurangan->unit_kerja_id && $pengurangan->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pengurangan $pengurangan): bool
    {
        // Write-Own: Hanya bisa delete pengurangan milik unit sendiri (jika masih pending)
        return $user->unit_kerja_id === $pengurangan->unit_kerja_id && $pengurangan->status === 'pending';
    }

    /**
     * Determine whether the user can approve the model.
     * Only kepala_bagian can approve transactions from their own unit.
     */
    public function approve(User $user, Pengurangan $pengurangan): bool
    {
        return $user->unit_kerja_id === $pengurangan->unit_kerja_id 
            && $user->role === 'kepala_bagian';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Pengurangan $pengurangan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Pengurangan $pengurangan): bool
    {
        return false;
    }
}
