<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $guarded = [];

    /**
     * Get the unit kerja that owns this barang.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Get the jenis barang for this barang.
     */
    public function jenisBarang(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_id');
    }

    /**
     * Get the penerimaan details for this barang.
     */
    public function penerimaanDetail(): HasMany
    {
        return $this->hasMany(PenerimaanDetail::class, 'barang_id');
    }

    /**
     * Get the pengurangan details for this barang.
     */
    public function penguranganDetail(): HasMany
    {
        return $this->hasMany(PenguranganDetail::class, 'barang_id');
    }

    /**
     * Get the stock opname for this barang.
     */
    public function stockOpname(): HasMany
    {
        return $this->hasMany(StockOpname::class, 'barang_id');
    }

    /**
     * Get the display name combining unit and barang.
     */
    public function getFullNameAttribute(): string
    {
        return "[{$this->unitKerja->nama_unit}] {$this->nama_barang}";
    }
}
