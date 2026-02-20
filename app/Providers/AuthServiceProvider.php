<?php

namespace App\Providers;

use App\Models\Barang;
use App\Models\Pengurangan;
use App\Models\Penerimaan;
use App\Models\StockOpname;
use App\Policies\BarangPolicy;
use App\Policies\PenerimaanPolicy;
use App\Policies\PenguranganPolicy;
use App\Policies\StockOpnamePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Barang::class => BarangPolicy::class,
        Penerimaan::class => PenerimaanPolicy::class,
        Pengurangan::class => PenguranganPolicy::class,
        StockOpname::class => StockOpnamePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates if needed
        Gate::define('is-kepala-bagian', function ($user) {
            return $user->role === 'kepala_bagian';
        });

        Gate::define('is-pengurus-barang', function ($user) {
            return $user->role === 'pengurus_barang';
        });

        Gate::define('is-super-admin', function ($user) {
            return $user->role === 'super_admin';
        });
    }
}
