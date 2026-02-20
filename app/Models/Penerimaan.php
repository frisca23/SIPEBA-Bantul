<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaan';
    protected $guarded = [];

    protected $casts = [
        'tgl_dokumen' => 'date',
        'tahun_anggaran' => 'integer',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the unit kerja that owns this penerimaan.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Get the user who created this penerimaan.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified this penerimaan.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the penerimaan details.
     */
    public function detail(): HasMany
    {
        return $this->hasMany(PenerimaanDetail::class, 'penerimaan_id');
    }

    /**
     * Get total value of this penerimaan.
     */
    public function getTotalValueAttribute(): float
    {
        return $this->detail()->sum('total_harga') ?? 0;
    }

    /**
     * Check if this penerimaan can be edited.
     */
    public function canEdit(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this penerimaan can be approved.
     */
    public function canApprove(): bool
    {
        return $this->status === 'pending' && $this->detail()->count() > 0;
    }
}
