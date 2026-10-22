<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_detail', function (Blueprint $table) {
            // 'tidak_ada' = alat kembali baik-baik saja, tidak perlu ganti rugi
            // 'menunggu'  = alat rusak/hilang, menunggu siswa mengganti
            // 'lunas'     = sudah diganti/dibayar, kasus selesai
            $table->enum('status_ganti_rugi', ['tidak_ada', 'menunggu', 'lunas'])
                ->default('tidak_ada')->after('kondisi_kembali');

            $table->text('catatan_ganti_rugi')->nullable()->after('status_ganti_rugi');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_detail', function (Blueprint $table) {
            $table->dropColumn(['status_ganti_rugi', 'catatan_ganti_rugi']);
        });
    }
};
