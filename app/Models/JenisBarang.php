<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisBarang extends Model
{
    use HasFactory;

    protected $table = 'jenis_barang';
    protected $guarded = [];

    /**
     * Get the barang for this jenis barang.
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'jenis_id');
    }
}
