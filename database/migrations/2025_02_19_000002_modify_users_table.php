<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->enum('role', ['kepala_bagian', 'pengurus_barang', 'super_admin'])->default('pengurus_barang')->after('password');
            $table->foreignId('unit_kerja_id')->nullable()->after('role')->constrained('unit_kerja')->onDelete('cascade');
            $table->dropColumn('email');
            $table->dropColumn('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor('unit_kerja_id');
            $table->dropColumn('username');
            $table->dropColumn('role');
            $table->dropColumn('unit_kerja_id');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
        });
    }
};
