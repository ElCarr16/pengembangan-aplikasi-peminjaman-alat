<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita gunakan nullable agar Admin/Petugas tidak error saat daftar,
            // tapi kita paksa (required) untuk Peminjam di level aplikasi.
            $table->date('tanggal_lahir')->after('password')->nullable();
            $table->text('alamat')->after('tanggal_lahir')->nullable();
            $table->string('kota')->after('alamat')->nullable();
            $table->string('provinsi')->after('kota')->nullable();
            $table->string('kode_pos', 10)->after('provinsi')->nullable();
            $table->string('nomor_telepon', 20)->after('kode_pos')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tanggal_lahir', 'alamat', 'kota', 'provinsi', 'kode_pos', 'nomor_telepon']);
        });
    }
};
