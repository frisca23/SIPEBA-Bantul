<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName(): string
    {
        return $this->getKeyName();
    }

    /**
     * Get the unit kerja that owns the user.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Get the penerimaan created by this user.
     */
    public function penerimaanCreated(): HasMany
    {
        return $this->hasMany(Penerimaan::class, 'created_by');
    }

    /**
     * Get the penerimaan verified by this user.
     */
    public function penerimaanVerified(): HasMany
    {
        return $this->hasMany(Penerimaan::class, 'verified_by');
    }

    /**
     * Get the pengurangan created by this user.
     */
    public function penguranganCreated(): HasMany
    {
        return $this->hasMany(Pengurangan::class, 'created_by');
    }

    /**
     * Get the pengurangan verified by this user.
     */
    public function penguranganVerified(): HasMany
    {
        return $this->hasMany(Pengurangan::class, 'verified_by');
    }

    /**
     * Get the stock opname created by this user.
     */
    public function stockOpnameCreated(): HasMany
    {
        return $this->hasMany(StockOpname::class, 'created_by');
    }

    /**
     * Get the stock opname verified by this user.
     */
    public function stockOpnameVerified(): HasMany
    {
        return $this->hasMany(StockOpname::class, 'verified_by');
    }
}

