<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';
    protected $guarded = [];

    /**
     * Get the users for this unit kerja.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'unit_kerja_id');
    }

    /**
     * Get the barang for this unit kerja.
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'unit_kerja_id');
    }

    /**
     * Get the penerimaan for this unit kerja.
     */
    public function penerimaan(): HasMany
    {
        return $this->hasMany(Penerimaan::class, 'unit_kerja_id');
    }

    /**
     * Get the pengurangan for this unit kerja.
     */
    public function pengurangan(): HasMany
    {
        return $this->hasMany(Pengurangan::class, 'unit_kerja_id');
    }

    /**
     * Get the stock opname for this unit kerja.
     */
    public function stockOpname(): HasMany
    {
        return $this->hasMany(StockOpname::class, 'unit_kerja_id');
    }
}
