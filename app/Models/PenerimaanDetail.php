<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenerimaanDetail extends Model
{
    use HasFactory;

    protected $table = 'penerimaan_detail';
    protected $guarded = [];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Get the penerimaan that owns this detail.
     */
    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    /**
     * Get the barang for this detail.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
