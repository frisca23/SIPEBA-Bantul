<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenguranganDetail extends Model
{
    use HasFactory;

    protected $table = 'pengurangan_detail';
    protected $guarded = [];

    /**
     * Get the pengurangan that owns this detail.
     */
    public function pengurangan(): BelongsTo
    {
        return $this->belongsTo(Pengurangan::class, 'pengurangan_id');
    }

    /**
     * Get the barang for this detail.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
