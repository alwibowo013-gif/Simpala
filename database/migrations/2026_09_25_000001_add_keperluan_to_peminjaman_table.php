<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Kolom ini terlewat di rancangan ERD awal -- FR-03 butuh siswa mengisi
            // "keperluan" peminjaman, jadi kita tambahkan sekarang.
            $table->text('keperluan')->nullable()->after('tanggal_kembali_rencana');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('keperluan');
        });
    }
};
