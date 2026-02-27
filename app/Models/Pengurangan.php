<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengurangan extends Model
{
    use HasFactory;

    protected $table = 'pengurangan';
    protected $guarded = [];

    protected $casts = [
        'tgl_keluar' => 'date',
        'tgl_serah' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the unit kerja that owns this pengurangan.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Get the user who created this pengurangan.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified this pengurangan.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the pengurangan details.
     */
    public function detail(): HasMany
    {
        return $this->hasMany(PenguranganDetail::class, 'pengurangan_id');
    }

    /**
     * Get total quantity of this pengurangan.
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->detail()->sum('jumlah_kurang') ?? 0;
    }

    /**
     * Check if this pengurangan can be edited.
     */
    public function canEdit(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this pengurangan can be approved.
     */
    public function canApprove(): bool
    {
        return $this->status === 'pending' && $this->detail()->count() > 0;
    }
}
