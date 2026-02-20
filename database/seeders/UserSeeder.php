<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitKerjas = UnitKerja::all();

        foreach ($unitKerjas as $unit) {
            // Create kepala_bagian for each unit - simple username like kepala_1, kepala_2, etc
            User::firstOrCreate(
                [
                    'username' => 'kepala_' . $unit->id,
                    'unit_kerja_id' => $unit->id,
                ],
                [
                    'name' => 'Kepala ' . $unit->nama_unit,
                    'username' => 'kepala_' . $unit->id,
                    'password' => Hash::make('password'),
                    'role' => 'kepala_bagian',
                    'unit_kerja_id' => $unit->id,
                ]
            );

            // Create pengurus_barang for each unit - simple username like pengurus_1, pengurus_2, etc
            User::firstOrCreate(
                [
                    'username' => 'pengurus_' . $unit->id,
                    'unit_kerja_id' => $unit->id,
                ],
                [
                    'name' => 'Pengurus Barang ' . $unit->nama_unit,
                    'username' => 'pengurus_' . $unit->id,
                    'password' => Hash::make('password'),
                    'role' => 'pengurus_barang',
                    'unit_kerja_id' => $unit->id,
                ]
            );
        }

        // Create super admin
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'unit_kerja_id' => null,
            ]
        );
    }
}
